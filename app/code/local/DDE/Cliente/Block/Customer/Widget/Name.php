<?php
/**
 * DDE_Cliente extensio for Magento
 *
 * @category   DDE
 * @package    DDE_Cliente
 * @version    1.1.0
 */
require_once 'Mage/Customer/Block/Widget/Name.php';
class DDE_Cliente_Block_Customer_Widget_Name extends Mage_Customer_Block_Widget_Name
{
    public function _construct(){
        parent::_construct();
        $this->setTemplate('cliente/customer/widget/name.phtml');
    }
}
