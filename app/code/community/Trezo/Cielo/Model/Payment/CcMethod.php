<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

use Cielo_Api_Request_CieloRequestException as ApiError;

/**
 * Cielo Cc payment model
 */
class Trezo_Cielo_Model_Payment_CcMethod extends Mage_Payment_Model_Method_Cc
{
    const PAYMENT_METHOD = 'cielo_cc';
    /**
     * Payment Code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD;
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canVoid = true;
    protected $_canUseInternal = true;
    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = true;

    protected $_formBlockType = 'trezo_cielo/form_cc';
    protected $_infoBlockType = 'trezo_cielo/info_cc';

    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }

        $info = $this->getInfoInstance();
        $info->setCcType($data->getCcType())
            ->setCcOwner($data->getCcOwner())
            ->setCcLast4(substr($data->getCcNumber(), -4))
            ->setCcExpMonth($data->getCcExpMonth())
            ->setCcExpYear($data->getCcExpYear());

        $info->addData($data->getData())
            ->setAdditionalInformation('cc_installments', $data->getInstallments());

        return $this;
    }

    public function getConfigData($field, $storeId = null)
    {
        if (($field == 'order_status') && (parent::getConfigData('payment_action') == self::ACTION_AUTHORIZE_CAPTURE)) {
            return null;
        }

        if ($field == 'payment_action' && Mage::helper('trezo_cielo')->isAntiFraud()) {
            return self::ACTION_AUTHORIZE;
        }

        return parent::getConfigData($field, $storeId);
    }

    /**
     * Authorize payment abstract method
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function _authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);

        try {
            $transaction = Mage::getModel('trezo_cielo/cielo_creditCardTransaction', $payment);
            $response = $transaction->getResponseTransaction();
            $responsePayment = $response->getPayment();

            if (!Mage::helper('trezo_cielo')->validateStatus($responsePayment->getStatus())) {
                Mage::throwException("Erro retornado Cielo: " . $responsePayment->getReturnMessage());
            }

            // save response
            $payment->setAdditionalInformation('autorization_code', $responsePayment->getAuthorizationCode());
            $payment->setAdditionalInformation('payment_id', $responsePayment->getPaymentId());
            $payment->setAdditionalInformation('tid', $responsePayment->getTid());
            $payment->setAdditionalInformation('response', serialize($response));

            // save transaction
            $payment->setTransactionId($responsePayment->getPaymentId())
                ->setParentTransactionId($payment->getTransactionId())
                ->setTxnType(Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH)
                ->setIsTransactionClosed(false);
        } catch (ApiError $e) {
            Mage::throwException("Erro retornado Cielo: " . $e->getMessage());
        } catch (Exception $error) {
            Mage::throwException($error->getMessage());
        }

        return $this;
    }

    /**
     * Capture payment method
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::capture($payment, $amount);

        $transaction = Mage::getModel('sales/order_payment_transaction');

        //Pega os dados de autorização
        $authorizationTransaction = $payment->getAuthorizationTransaction();

        //Se a transação ainda não foi autorizada, autoriza e captura de uma vez só,
        // por isso a captura não deve ser executada depois da autorização (o que está no else)
        if (!$authorizationTransaction) {
            $creditCardTransaction = Mage::getModel('trezo_cielo/cielo_creditCardTransaction', $payment);

            try {
                $response = $creditCardTransaction->getResponseTransaction();
            } catch (ApiError $e) {
                Mage::throwException("Erro retornado Cielo: " . $e->getCieloError()->getMessage());
            }

            $responsePayment = $response->getPayment();

            if (!Mage::helper('trezo_cielo')->validateStatus($responsePayment->getStatus())) {
                Mage::throwException("Erro retornado Cielo: " . $responsePayment->getReturnMessage());
            }

            // save response
            $payment->setAdditionalInformation('autorization_code', $responsePayment->getAuthorizationCode());
            $payment->setAdditionalInformation('payment_id', $responsePayment->getPaymentId());
            $payment->setAdditionalInformation('tid', $responsePayment->getTid());

            $transaction->setTransactionId($responsePayment->getPaymentId() . '-capture');
            $payment->setTransactionId($responsePayment->getPaymentId() . '-capture');

        //caso já tenha sido autorizada mas não capturada (nunca deve executar)
        } else {
            /** @var Trezo_Cielo_Model_Cielo_CaptureTransaction $captureTransaction */
            $captureTransaction = Mage::getModel('trezo_cielo/cielo_captureTransaction', $payment);

            try {
                $response = $captureTransaction->getResponseTransaction();
            } catch (ApiError $e) {
                Mage::throwException("Erro retornado Cielo: " . $e->getCieloError()->getMessage());
            }

            if (!Mage::helper('trezo_cielo')->validateStatus($response->getStatus())) {
                Mage::throwException("Erro retornado Cielo: " . $response->getReturnMessage());
            }

            $transaction->setTxnId($payment->getAdditionalInformation('payment_id') . '-capture');
            $transaction->setParentId($authorizationTransaction->getId());
            $transaction->setParentTxnId($authorizationTransaction->getTxnId());
        }

        //Salva a quantia capturada
        $payment->setCapturedAmount($amount);

        //Registra a transação
        $transaction->setOrderPaymentObject($payment);
        $transaction->setTxnType(Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE);
        $transaction->setAdditionalInformation('response', serialize($response));
        $transaction->setIsClosed(true);
        $transaction->save();
        
        try {
            $creditCardTransaction->captureSale();
        } catch (Exception $e) {
        }

        return $this;
    }

    /**
     * Capture payment method
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function capture(Varien_Object $payment, $amount)
    {
        parent::capture($payment, $amount);

        $transaction = Mage::getModel('sales/order_payment_transaction');

        //Pega os dados de autorização
        $authorizationTransaction = $payment->getAuthorizationTransaction();

        //Se a transação ainda não foi autorizada, autoriza e captura de uma vez só,
        // por isso a captura não deve ser executada depois da autorização (o que está no else)
        if (!$authorizationTransaction) {
            $creditCardTransaction = Mage::getModel('trezo_cielo/cielo_creditCardTransaction', $payment);

            try {
                $response = $creditCardTransaction->getResponseTransaction();
            } catch (ApiError $e) {
                Mage::throwException("Erro retornado Cielo: " . $e->getCieloError()->getMessage());
            }

            $responsePayment = $response->getPayment();

            if (!Mage::helper('trezo_cielo')->validateStatus($responsePayment->getStatus())) {
                Mage::throwException("Erro retornado Cielo: " . $responsePayment->getReturnMessage());
            }

            // save response
            $payment->setAdditionalInformation('autorization_code', $responsePayment->getAuthorizationCode());
            $payment->setAdditionalInformation('payment_id', $responsePayment->getPaymentId());
            $payment->setAdditionalInformation('tid', $responsePayment->getTid());

            $transaction->setTransactionId($responsePayment->getPaymentId() . '-capture');
            $payment->setTransactionId($responsePayment->getPaymentId() . '-capture');

        //caso já tenha sido autorizada mas não capturada (nunca deve executar)
        } else {
            /** @var Trezo_Cielo_Model_Cielo_CaptureTransaction $captureTransaction */
            $captureTransaction = Mage::getModel('trezo_cielo/cielo_captureTransaction', $payment);

            try {
                $response = $captureTransaction->getResponseTransaction();
            } catch (ApiError $e) {
                Mage::throwException("Erro retornado Cielo: " . $e->getCieloError()->getMessage());
            }

            if (!Mage::helper('trezo_cielo')->validateStatus($response->getStatus())) {
                Mage::throwException("Erro retornado Cielo: " . $response->getReturnMessage());
            }

            $transaction->setTxnId($payment->getAdditionalInformation('payment_id') . '-capture');
            $transaction->setParentId($authorizationTransaction->getId());
            $transaction->setParentTxnId($authorizationTransaction->getTxnId());
        }

        //Salva a quantia capturada
        $payment->setCapturedAmount($amount);

        //Registra a transação
        $transaction->setOrderPaymentObject($payment);
        $transaction->setTxnType(Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE);
        $transaction->setAdditionalInformation('response', serialize($response));
        $transaction->setIsClosed(true);
        $transaction->save();

        return $this;
    }

    /**
     * Refund specified amount for payment
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function refund(Varien_Object $payment, $amount)
    {
        parent::refund($payment, $amount);

        /** @var Trezo_Cielo_Model_Cielo_CancelTransaction $cancelTransaction */
        $cancelTransaction = Mage::getModel('trezo_cielo/cielo_cancelTransaction', $payment);
        $cancelTransaction->cancelTransaction($payment, $amount);

        return $this;
    }

    /**
     * Cancel payment abstract method
     *
     * @param Varien_Object $payment
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function cancel(Varien_Object $payment)
    {
        parent::cancel($payment);
        return $this->void($payment);
    }

    /**
     * Void payment abstract method
     *
     * @param Varien_Object $payment
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function void(Varien_Object $payment)
    {
        parent::void($payment);
        return $this->refund($payment, $payment->getAmountAuthorized());
    }

    public function validate()
    {
        if (!$this->getConfigData('validate')) {
            return $this;
        }

        return parent::validate();
    }

    public function getVerificationRegEx()
    {
        $verificationExpList = parent::getVerificationRegEx();
        $verificationExpList['HI'] = '/^[0-9]{3}$/'; // Hipercard
        $verificationExpList['EL'] = '/^[0-9]{3}$/'; // Elo
        $verificationExpList['DN'] = '/^[0-9]{3}$/'; // Diners
        $verificationExpList['JC'] = '/^[0-9]{3}$/'; // JCB
        $verificationExpList['DC'] = '/^[0-9]{4}$/'; // Discover
        $verificationExpList['AU'] = '/^[0-9]{3}$/'; // Aura

        return $verificationExpList;
    }
}
