<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     Leonardo Lopes de Albuquerque <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Block_Onepage_Failure extends Mage_Checkout_Block_Onepage_Failure
{

    protected $_template = "trezo/cielo/failure.phtml";

    public function getOrder()
    {
        $orderId = $this->getRealOrderId();
        return Mage::getModel('sales/order')->loadByIncrementId($orderId);
    }

    public function isDebitCardTransaction()
    {
        return $this->getOrder()->getPayment()->getMethod() === Trezo_Cielo_Model_Payment_DcMethod::PAYMENT_METHOD;
    }

    public function getStatus()
    {
        $status = $this->getOrder()->getPayment()->getStatus();

        switch ($status) {
            case 0:
                return $this->__("Waiting status update");
            case 1:
                return $this->__("Payment can be captured or defined as paid");
            case 2:
                return $this->__("Payment confirmed and finished");
            case 3:
                return $this->__("Payment denied by authorizer");
            case 10:
                return $this->__("Payment canceled");
            case 11:
                return $this->__("Payment caled after 23:59 of the authorization day");
            case 12:
                return $this->__("Waiting financial institution status");
            case 13:
                return $this->__("Payment canceled by processing failure");
            case 20:
                return $this->__("Scheduled recurrence");
            default:
                return $this->__("Unrecognized error");
        }

    }
}