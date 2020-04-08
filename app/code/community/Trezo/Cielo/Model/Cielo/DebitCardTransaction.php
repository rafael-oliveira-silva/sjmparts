<?php
/**
 * Created by PhpStorm.
 * User: leonardo
 * Date: 27/11/17
 * Time: 16:09
 */

class Trezo_Cielo_Model_Cielo_DebitCardTransaction extends Trezo_Cielo_Model_Cielo_CardTransaction
{
    public function __construct(Mage_Sales_Model_Order_Payment $payment)
    {
        parent::__construct($payment);
        self::$sale = new Cielo_Api_Sale($this->order->getIncrementId());

        $this->setCieloCustomer();
        $this->setTransactionData();
    }

    private function setTransactionData()
    {
        /** @var Trezo_Cielo_Helper_Data $helper */
        $helper = Mage::helper('trezo_cielo');
        /** @var Cielo_Api_Payment $payment */
        $payment = self::$sale->payment($helper->treatToCents($this->order->getGrandTotal()));

        $dcType = $this->payment->getCcType();
        $dcTypeCielo = $this->getCieloCardType($dcType);
        $dcExpMonth = str_pad($this->payment->getCcExpMonth(), 2, '0', STR_PAD_LEFT);
        $dcExpYear = $this->payment->getCcExpYear();

        $payment->setType(Cielo_Api_Payment::PAYMENTTYPE_DEBITCARD)
            // precisa de uma url de retorno, mesmo que não seja usada
            ->setReturnUrl(Mage::getUrl('checkout/onepage/validateDebitCardOrder'))
            ->setSoftDescriptor($this->getSoftDescriptor())
            ->setCapture($this->isCapture())
            ->debitCard($this->payment->getCcCid(), $dcTypeCielo)
            ->setExpirationDate("{$dcExpMonth}/{$dcExpYear}")
            ->setCardNumber(preg_replace('/[^0-9]+/', '', $this->payment->getCcNumber()))
            ->setHolder($this->payment->getCcOwner());

    }

    /**
     * Return soft descriptor to show in credit card bill limited by 13 characters
     * @return string soft descriptor showed on credit card bill
     */
    public function getSoftDescriptor()
    {
        return substr($this->getConfig('soft_descriptor', 'cielo_dc'), 0, 13);
    }

    /**
     * Return true if flag capture is enabled else return false
     *
     * @return boolean return if payment action is to capture
     */
    public function isCapture()
    {
        $paymentAction = $this->getConfig('payment_action', 'cielo_dc');
        return $paymentAction == Trezo_Cielo_Model_Payment_DcMethod::ACTION_AUTHORIZE_CAPTURE;
    }

}