<?php
/**
 * DDE_Cliente extension for Magento
 *
 * @category   DDE
 * @package    DDE_Cliente
 * @version    1.1.0
 */
require_once 'Mage/Customer/Block/Widget/Taxvat.php';
class DDE_Cliente_Block_Customer_Widget_Taxvat extends Mage_Customer_Block_Widget_Taxvat
{
    public function _construct(){
        parent::_construct();
        $this->setTemplate('cliente/customer/widget/taxvat.phtml');
    }
}
