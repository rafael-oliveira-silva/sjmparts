<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

use Cielo_Api_CieloEcommerce as CieloEcommerce;

class Trezo_Cielo_Model_Cielo_CreditCardTransaction extends Trezo_Cielo_Model_Cielo_CardTransaction
{
    public function __construct(Mage_Sales_Model_Order_Payment $payment)
    {
        parent::__construct($payment);
        self::$sale = new Cielo_Api_Sale($this->order->getIncrementId());

        $this->setCieloCustomer();
        $this->setTransactionData();
        if ($this->isAntiFraud()) {
            $this->setCieloShoppingCart();
        }
    }

    public function captureSale()
    {
        $sale =  new CieloEcommerce($this->merchant, $this->environment);
        $paymentId = $this->payment->getAdditionalInformation('payment_id');
        $sale->captureSale($paymentId);
    }

    private function setTransactionData()
    {
        // Cria objeto do cartão de crédito
        $helper = Mage::helper('trezo_cielo');

        $installments = $this->payment->getAdditionalInformation('cc_installments');
        $interestPercentage = $helper->getInterestPercentage($installments);

        $realGrandTotal = $helper->getGrandTotalWithInterest(
            $this->order->getGrandTotal(),
            $installments,
            $interestPercentage
        );

        $payment = self::$sale->payment($helper->treatToCents($realGrandTotal));

        $ccType = $this->payment->getCcType();
        $ccTypeCielo = $this->getCieloCardType($ccType);
        $ccExpMonth = str_pad($this->payment->getCcExpMonth(), 2, '0', STR_PAD_LEFT);
        $ccExpYear = $this->payment->getCcExpYear();

        $payment->setType(Cielo_Api_Payment::PAYMENTTYPE_CREDITCARD)
            ->setInstallments($installments)
            ->setSoftDescriptor($this->getSoftDescriptor())
            ->setCapture($this->isCapture())
            ->creditCard($this->payment->getCcCid(), $ccTypeCielo)
            ->setExpirationDate("{$ccExpMonth}/{$ccExpYear}")
            ->setCardNumber(preg_replace('/[^0-9]+/', '', $this->payment->getCcNumber()))
            ->setHolder($this->payment->getCcOwner());

        if ($this->isAntiFraud()) {
            $this->setCieloFraudAnalysis();
        }
    }

    /**
     * Return soft descriptor to show in credit card bill limited by 13 characters
     * @return string soft descriptor showed on credit card bill
     */
    public function getSoftDescriptor()
    {
        return substr($this->getConfig('soft_descriptor', 'cielo_cc'), 0, 13);
    }

    public function isCapture()
    {
        return true;
        // $paymentAction = $this->getConfig('payment_action', 'cielo_cc');
        // return $paymentAction == Trezo_Cielo_Model_Payment_CcMethod::ACTION_AUTHORIZE_CAPTURE;
    }
}
