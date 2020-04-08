<?php
/**
* Trezo Soluções Web
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to https://www.trezo.com.br for more information.
*
* @category Trezo
* @package Trezo_Cielo
*
* @copyright Copyright (c) 2017 Trezo Soluções Web. (https://www.trezo.com.br)
*
* @author Trezo Core Team <contato@trezo.com.br>
*/

class Trezo_Cielo_Adminhtml_CieloController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return true;
    }
    
    public function indexAction()
    {
        $order = Mage::getModel('sales/order')->load($this->getRequest()->getParam('order_id'));
        $queryOrder = Mage::getModel('trezo_cielo/cielo_queryTransaction', $order->getPayment())->getResponseTransaction();
        $paymentMethod = $order->getPayment()->getMethod();
        $this->loadLayout();
        $this->getLayout()->getBlock('cielo.transaction')
                    ->setData('infos', $queryOrder)
                    ->setData('payment_method', $paymentMethod)
                    ->toHtml();
        $this->renderLayout();
    }
}
