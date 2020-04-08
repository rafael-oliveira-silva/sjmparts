<?php
require_once "Mage/Customer/controllers/AccountController.php";  
class DDE_Juridico_Customer_AccountController extends Mage_Customer_AccountController{
	public function loginPostAction(){
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
					
					$_customer = Mage::getModel('customer/customer');
					$_customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($login['username']);
					
					if ($_customer->getGroupId() == 5 || $_customer->getGroupId() == 4) {
						$message = Mage::getSingleton('core/session')->addError($this->__('Prezado cliente, efetue seu cadastro como Pessoa Física.'));
						$session->logout()->renewSession();
						header('Location: '. Mage::getUrl('customer/account/login'));
						exit;
					}
					
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }
		
		// Zend_Debug::dump($session->getCustomer());
		// exit;
		
        $this->_loginPostRedirect();
    }
}