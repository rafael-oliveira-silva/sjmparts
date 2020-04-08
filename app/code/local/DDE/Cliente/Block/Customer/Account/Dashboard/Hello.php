<?php
/**
 * DDE_Cliente extension for Magento
 *
 * @category   DDE
 * @package    DDE_Cliente
 * @version    1.1.0
 */
require_once 'Mage/Customer/Block/Account/Dashboard/Hello.php';
class DDE_Cliente_Block_Customer_Account_Dashboard_Hello extends Mage_Customer_Block_Account_Dashboard_Hello
{
    public function getCustomerName(){
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		
		if($customer->getTipo() == 2) return $customer->getFirstname();
		else return $customer->getName();
    }
}
