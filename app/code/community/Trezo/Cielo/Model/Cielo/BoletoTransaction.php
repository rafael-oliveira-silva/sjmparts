<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

use Cielo_Api_Sale as Sale;
use Cielo_Api_CieloEcommerce as CieloEcommerce;

class Trezo_Cielo_Model_Cielo_BoletoTransaction extends Trezo_Cielo_Model_Cielo_AbstractTransaction
{

    public function __construct(Mage_Sales_Model_Order_Payment $payment)
    {
        parent::__construct($payment);
        self::$sale = new Cielo_Api_Sale($this->order->getIncrementId());

        $this->setCieloCustomer();
        /* @TODO: ANTIFRAUD */
        //$this->setCieloShoppingCart();

        $this->setTransactionData();
    }

    private function setTransactionData()
    {
        // Cria objeto do cartão de crédito
        $payment = self::$sale->payment(
            Mage::helper('trezo_cielo')
                ->treatToCents($this->order->getGrandTotal())
        );

        $payment->setType(Cielo_Api_Payment::PAYMENTTYPE_BOLETO)
                ->setProvider($this->getConfig('provider', 'cielo_boleto'))
                ->setExpirationDate($this->getExpirationDate())
                ->setInstructions($this->getConfig('instructions', 'cielo_boleto'))
                ->setBoletoNumber($this->order->getIncrementId());
    }

    public function getExpirationDate()
    {
        $addDays = $this->getConfig('days_to_expiration', 'cielo_boleto');
        $dateTime = new DateTime();
        $dateTime->add(new DateInterval("P{$addDays}D"));
        return $dateTime->format('m-d-Y');
    }

    public function getResponseTransaction()
    {
        // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
        $request = new CieloEcommerce($this->merchant, $this->environment);
        return $request->createSale(self::$sale);
    }
}