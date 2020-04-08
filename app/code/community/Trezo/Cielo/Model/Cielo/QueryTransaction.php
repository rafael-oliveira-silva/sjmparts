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

class Trezo_Cielo_Model_Cielo_QueryTransaction extends Trezo_Cielo_Model_Cielo_AbstractTransaction
{
    private $paymentId;

    public function __construct(Mage_Sales_Model_Order_Payment $payment)
    {
        parent::__construct($payment);

        // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
        self::$sale =  new CieloEcommerce($this->merchant, $this->environment);
        $this->setTransactionData();
    }

    private function setTransactionData()
    {
        // Define dados da requisição
        $this->paymentId = $this->payment->getAdditionalInformation('payment_id');
    }

    public function getResponseTransaction()
    {
        return self::$sale->getSale($this->paymentId);
    }
}