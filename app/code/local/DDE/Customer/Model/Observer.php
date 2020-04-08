<?php
class DDE_Customer_Model_Observer extends Varien_Event_Observer{
	public function __construct(){
		
	}
	
	public function registrationSuccess($observer) {

		$page_alias = '';
		// extract customer data from event
		$customer = $observer->getCustomer();
		// Zend_Debug::dump(Mage::getSingleton('core/session')->getCustomerType());exit;
		$page_alias = Mage::getSingleton('core/session')->getCustomerType();
		
		if ($page_alias == 'retailers') {
			$customer->setGroupId(3);
		}
		
		return $this;
	}
}