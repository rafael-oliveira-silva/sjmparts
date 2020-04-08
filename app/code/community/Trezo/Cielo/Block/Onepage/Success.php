<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     Bruno Roeder <contato@trezo.com.br>
 *
 */
class Trezo_Cielo_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = "trezo/cielo/success.phtml";

    public function getOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        return Mage::getModel('sales/order')->loadByIncrementId($orderId);
    }

    public function canShowBoleto()
    {
        return $this->getOrder()->getPayment()->getMethod() === Trezo_Cielo_Model_Payment_BoletoMethod::PAYMENT_METHOD;
    }

    public function canShowCcInfo()
    {
        return $this->getOrder()->getPayment()->getMethod() === Trezo_Cielo_Model_Payment_CcMethod::PAYMENT_METHOD;
    }

    public function getBoletoUrl()
    {
        return $this->getOrder()->getPayment()->getAdditionalInformation('boleto_url');
    }

    public function getInfos($info)
    {
        return $this->getOrder()->getPayment()->getAdditionalInformation($info);
    }

    public function canShowDcInfo()
    {
        return $this->getOrder()->getPayment()->getMethod() === Trezo_Cielo_Model_Payment_DcMethod::PAYMENT_METHOD;
    }
}
