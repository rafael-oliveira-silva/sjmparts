<?php

/**
 * Created by PhpStorm.
 * User: leonardo
 * Date: 24/11/17
 * Time: 11:25
 */
class Trezo_Cielo_Model_Payment_DcMethod extends Mage_Payment_Model_Method_Abstract
{
    const PAYMENT_METHOD = 'cielo_dc';
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

    protected $_formBlockType = 'trezo_cielo/form_dc';
    protected $_infoBlockType = 'trezo_cielo/info_dc';

    private static $authenticationUrl;

    public function capture(Varien_Object $payment, $amount)
    {

        parent::capture($payment, $amount);
        try {
            /** @var Trezo_Cielo_Model_Cielo_DebitCardTransaction $transaction */
            $transaction = Mage::getModel('trezo_cielo/cielo_debitCardTransaction', $payment);
            $response = $transaction->getResponseTransaction();
            $responsePayment = $response->getPayment();

            if ($responsePayment->getStatus() !== 0 && !Mage::helper('trezo_cielo')->validateStatus($responsePayment->getStatus())) {
                Mage::throwException("Erro retornado Cielo: " . $responsePayment->getReturnMessage());
            }

            // save response
            $payment->setAdditionalInformation('payment_type', 'cielo_debit_card');
            $payment->setAdditionalInformation('payment_id', $responsePayment->getPaymentId());
            $payment->setAdditionalInformation('tid', $responsePayment->getTid());
            $payment->setAdditionalInformation('response', serialize($response));
            $payment->setAdditionalInformation('authentication_url', $responsePayment->getAuthenticationUrl());

            Mage::getSingleton('customer/session')->setRedirectUrl($responsePayment->getAuthenticationUrl());


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
        $info->setCcType($data->getDcType())
            ->setCcOwner($data->getDcOwner())
            ->setCcLast4(substr($data->getDcNumber(), -4))
            ->setCcExpMonth($data->getDcExpMonth())
            ->setCcExpYear($data->getDcExpYear())
            ->setCcNumber($data->getDcNumber())
            ->setCcCid($data->getDcCid());

        $info->addData($data->getData());

        return $this;
    }

    public function getOrderPlaceRedirectUrl()
    {
        Mage::Log('returning redirect url:: ' . Mage::getSingleton('customer/session')->getRedirectUrl());
        return Mage::getSingleton('customer/session')->getRedirectUrl();
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
}