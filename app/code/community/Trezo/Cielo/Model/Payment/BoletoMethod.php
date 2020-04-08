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
 * Cielo Boleto payment model
 */
class Trezo_Cielo_Model_Payment_BoletoMethod extends Mage_Payment_Model_Method_Abstract
{
    const PAYMENT_METHOD = 'cielo_boleto';
    /**
     * Paymento Code
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

    protected $_formBlockType = 'trezo_cielo/form_boleto';
    protected $_infoBlockType = 'trezo_cielo/info_boleto';

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
        return $this;
    }

    /**
     * Authorize payment abstract method
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);

        try {
            $transaction = Mage::getModel('trezo_cielo/cielo_boletoTransaction', $payment);
            $response = $transaction->getResponseTransaction();
            $boletoTransaction = $response->getPayment();

            // save response
            $payment->setAdditionalInformation('boleto_url', $boletoTransaction->getUrl());
            $payment->setAdditionalInformation('barcode', $boletoTransaction->getBarCodeNumber());
            $payment->setAdditionalInformation('payment_id', $boletoTransaction->getPaymentId());
            $payment->setAdditionalInformation('expiration_date', $boletoTransaction->getExpirationDate());
            $payment->setAdditionalInformation('boleto_number', $boletoTransaction->getBoletoNumber());
            $payment->setAdditionalInformation('response', serialize($response));

            // save transaction
            $payment->setTransactionId($boletoTransaction->getPaymentId())
                    ->setParentTransactionId($payment->getTransactionId())
                    ->setTxnType(Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH)
                    ->setIsTransactionClosed(false);
        } catch (ApiError $e) {
            Mage::throwException("Erro retornado Cielo: " . $e->getCieloError()->getMessage());
        } catch (Exception $error) {
            Mage::throwException($error->getMessage());
        }

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
        return parent::cancel($payment);
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
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }
}
