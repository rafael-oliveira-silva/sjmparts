<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */
require_once 'Mage/Contacts/controllers/IndexController.php';

class VladimirPopov_WebForms_Contacts_IndexController extends Mage_Contacts_IndexController{
	protected function _isAllowed(){
		return Mage::getSingleton('admin/session')->isAllowed('webforms/forms');
	}
		
	public function indexAction()
	{
		// make it compatible with aheadworks help desk
		if(strstr($this->getFullActionName(),"contacts") && Mage::getStoreConfig('helpdeskultimate/modules/cf_enabled')){
			parent::indexAction();
			return;
		};
		
		Mage::register('show_form_name',true);
		$this->loadLayout();
		if(Mage::getStoreConfig('webforms/contacts/enable') && $this->getFullActionName() == 'contacts_index_index'){
			
			// remove default contacts
			$this->getLayout()->getBlock('contactForm')->setTemplate(false);
			
			// remove aheadworks antibot
			$aw_antibot = $this->getLayout()->getBlock('antibot');
			if($aw_antibot) 
				$aw_antibot->setTemplate(false);
			
			// add web-form to the layout
			$block = $this->getLayout()->createBlock('webforms/webforms','webforms',array(
				'template' => 'webforms/default.phtml',
				'webform_id' => Mage::getStoreConfig('webforms/contacts/webform')
			));
			$this->getLayout()->getBlock('content')->append($block);
		}
		$this->_initLayoutMessages('customer/session');
		$this->_initLayoutMessages('catalog/session');
		$this->renderLayout();
	}
	
}  
?>
