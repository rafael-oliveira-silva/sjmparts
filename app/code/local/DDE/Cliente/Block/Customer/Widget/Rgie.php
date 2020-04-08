<?php
/**
 * DDE_Cliente extension for Magento
 *
 * @category   DDE
 * @package    DDE_Cliente
 * @version    1.1.0
 */
class DDE_Cliente_Block_Customer_Widget_Rgie extends Mage_Customer_Block_Widget_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->setTemplate('cliente/customer/widget/rgie.phtml');
    }

    public function getCustomer(){
        return Mage::getSingleton('customer/session')->getCustomer();
    }
}
