<?php

class DDE_Cliente_Model_Observer{
	public function sendNewCustomerEmail(Varien_Event_Observer $observer){
		
		
		
			/* $mailTemplate = Mage::getModel('core/email_template')
				->loadDefault('account_new_retailer');
			$mailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_support/name'))
						 ->setSenderEmail(Mage::getStoreConfig('trans_email/ident_support/email'))
						 ->setTemplateSubject('Novo Atacadista Cadastrado');
			
			$mailTemplate->send('samir@18digital.com.br', 'Miltfort', $observer->getCustomer()); */
		
	}
	
	public function checkCustomerGroup(Varien_Event_Observer $observer){
		try{
			$customer = $observer->getCustomer();
			
			if( $customer->getTipo() == 2 ) $customer->setData('group_id', 4);
			
			$page_alias = '';
			// Zend_Debug::dump(Mage::getSingleton('core/session')->getCustomerType());exit;
			$page_alias = Mage::getSingleton('core/session')->getCustomerType();
			
			if ($page_alias == 'retailers') {
				$customer->setData('group_id', 3);
			}
			
		}catch(Exception $e){
			Mage::log('Cliente New Customer Group Check Failed: '.$e->getMessage());
		}
	}
}