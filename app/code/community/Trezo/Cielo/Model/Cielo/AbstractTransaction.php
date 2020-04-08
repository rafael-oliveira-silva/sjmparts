<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

abstract class Trezo_Cielo_Model_Cielo_AbstractTransaction extends Trezo_Cielo_Model_AbstractCielo
{
    protected static $sale;
    protected $order;
    protected $payment;
    protected $FraudAnalysis;

    public function __construct(Mage_Sales_Model_Order_Payment $payment)
    {
        parent::__construct();

        $this->payment = $payment;
        $this->order = $payment->getOrder();
        $this->FraudAnalysis = new stdClass();
    }

    protected function setCieloCustomer()
    {
        return new Trezo_Cielo_Model_Cielo_Transaction_Buyer($this->order, self::$sale);
    }

    protected function setCieloFraudAnalysis()
    {
        $browser = new stdClass();
        $shipping = new stdClass();

        $this->FraudAnalysis->Browser = $browser;
        $this->FraudAnalysis->Shipping = $shipping;
        $this->FraudAnalysis->Sequence = "AuthorizeFirst";
        $this->setCieloShoppingCart();
        self::$sale->getPayment()->FraudAnalysis = $this->FraudAnalysis;
    }

    protected function setCieloShoppingCart()
    {
        $shoppingCart = new Trezo_Cielo_Model_Cielo_Transaction_ShoppingCart($this->order);
        $shoppingCart = $shoppingCart->getShoppingCart();
        $this->FraudAnalysis->Cart = $shoppingCart;
    }

    public function isAntiFraud()
    {
        return Mage::helper('trezo_cielo')->isAntiFraud();
    }

    public function getNotificationUrl()
    {
        return Mage::getUrl('trezo_cielo/notification/index');
    }

    public function logResponse($data)
    {
        $this->log(':: LOG RESPONSE ::');
        $this->log($data);
    }

    public function logRequest($data)
    {
        $this->log(':: LOG REQUEST ::');
        $this->log($data);
    }

    public function log($data)
    {
        Mage::helper('trezo_cielo')->log($data);
    }

    abstract function getResponseTransaction();
}
