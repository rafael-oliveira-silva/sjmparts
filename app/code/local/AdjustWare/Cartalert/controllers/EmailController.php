<?php
class AdjustWare_Cartalert_EmailController extends Mage_Core_Controller_Front_Action
{
    public function saveAction()
    {
        $email = $this->getRequest()->getParam('email');
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        if ($email && Zend_Validate::is($email, 'EmailAddress') && $quote->getId()) {
            $billingAddress = $quote->getBillingAddress();
            if (!$billingAddress->getEmail()) {
                $billingAddress->setEmail($email)->save();
            }
        }

        $this->getResponse()->setBody('');
    }
}
