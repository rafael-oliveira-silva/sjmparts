<?php
class VladimirPopov_WebForms_Model_Message
	extends Mage_Core_Model_Abstract
{
	public function _construct(){
		parent::_construct();
		$this->_init('webforms/message');
	}	

	public function sendEmail(){
		$result = Mage::getModel('webforms/results')
			->load($this->getResultId());
		
		$email = $result->getCustomerEmail();	
		
		if(!$email) return false;
		
		$name = $result->getCustomerName();

		$webform = Mage::getModel('webforms/webforms')
			->setStoreId($result->getStoreId())
			->load($result->getWebformId());
				
		$emailSettings = $webform->getEmailSettings();
		
		$sender = Array(
			'name' => Mage::app()->getStore($this->getStoreId())->getFrontendName(),
			'email' => $emailSettings['email'],
		);
					
		if(Mage::getStoreConfig('webforms/email/email_from')){
			$sender['email'] = Mage::getStoreConfig('webforms/email/email_from');
		}

		$subject = $this->getEmailSubject($recipient);
		
		
		$varCustomer = new Varien_Object(array(
			'name'	=> $name
		));
		
		$varResult = new Varien_Object(array(
			'id'		=> $result->getId(),
			'subject'	=> $result->getEmailSubject(),
			'date'		=> Mage::helper('core')->formatDate($result->getCreatedTime()),
			'html'		=> $result->toHtml('customer')
		));
		
		$varReply = new Varien_Object(array(
			'date'		=> Mage::helper('core')->formatDate($this->getCreatedTime()),
			'message'	=> $this->getMessage(),
			'author'	=>	$this->getAuthor()
		));
		
		$vars = Array(
			'customer' 	=> $varCustomer,
			'result'	=> $varResult,
			'reply' 	=> $varReply
		);

		$customer = $this->getCustomer();

		if($customer){
			$customerObject = new Varien_Object();
			$customerObject->setData($customer->getData());
			$vars['customer'] = $customerObject;
		}
	
		$storeId = $result->getStoreId(); 
		
		$templateId = 'webforms_reply';
		
		if($webform->getEmailReplyTemplateId()){
			$templateId = $webform->getEmailReplyTemplateId();
		}

		$success = Mage::getModel('core/email_template')
				->setTemplateSubject($subject)
				->setReplyTo($result->getReplyTo('customer'))
				->sendTransactional($templateId, $sender, $email, $name, $vars, $storeId);

        if($_SERVER['REMOTE_ADDR'] == '201.6.130.202'){
            Zend_Debug::dump($result->getReplyTo('customer'));
            Zend_Debug::dump($email);
            Zend_Debug::dump($name);
            Zend_Debug::dump($sender);
            // Zend_Debug::dump($vars);
            // Zend_Debug::dump($storeId);
            Zend_Debug::dump($success->getData());
            exit;
        }
		return $success;
	}	
}
?>
