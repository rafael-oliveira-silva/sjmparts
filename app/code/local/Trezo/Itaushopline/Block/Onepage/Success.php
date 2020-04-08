<?php
/**
 * Trezo Itaushopline Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Itaushopline
 * @author     André Felipe <contato@trezo.com.br>
 *
 */
class Trezo_Itaushopline_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    public function getIncrementId()
    {
        return Mage::getSingleton('checkout/session')->getLastRealOrderId();
    }

    public function getOrder()
    {
        $orderId = $this->getIncrementId();
        return  Mage::getModel('sales/order')->loadByIncrementId($orderId);
    }

    public function canShowBoleto()
    {
        return $this->getOrder()->getPayment()->getMethod() === Trezo_Itaushopline_Model_Standard::PAYMENT_METHOD;
    }

    public function getBoletoUrl()
    {
        $numeroPedido = $this->getIncrementId();
        return Mage::getUrl("itaushopline/standard/show/pedido/{$numeroPedido}");
    }
}
