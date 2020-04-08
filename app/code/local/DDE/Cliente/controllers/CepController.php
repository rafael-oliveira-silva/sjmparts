<?php

/**
 *
 */
class DDE_Cliente_CepController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->consultarAction();
        return;
    }

    public function testeAction()
    {
        $products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        echo '<table>';
        echo '<tbody>';
        foreach ($products as $product) {
            echo '<tr>';
            echo '<td>';
            echo $product->getName();
            echo '</td>';
            echo '<td>';
            echo $product->getProductUrl();
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    
    public function consultarAction()
    {
        $request = $this->getRequest();
        $cep = str_replace('-', '', $request->getParam('cep'));
        
        // $url = 'http://cep.republicavirtual.com.br/web_cep.php?cep='.$cep.'&formato=xml';
        // $url = 'http://api.postmon.com.br/v1/cep/'.$cep.'?format=xml';
        $url = 'https://viacep.com.br/ws/'.$cep.'/json/';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        // $result = (array) simplexml_load_string(curl_exec($curl));
        $result = json_decode(curl_exec($curl), true);
        
        curl_close($curl);
        
        $result['cidade'] = $result['localidade'];
        
        echo json_encode($result);
    }

    /**
      * New subscription action
      */
    public function newsubscribeAction()
    {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session            = Mage::getSingleton('core/session');
            $customerSession    = Mage::getSingleton('customer/session');
            $email              = (string) $this->getRequest()->getPost('email');

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                    !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('This email address is already assigned to another user.'));
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $session->addSuccess($this->__('Confirmation request has been sent.'));
                } else {
                    $session->addSuccess($this->__('Thank you for your subscription.'));
                }
            } catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            } catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }
        $this->_redirectReferer();
    }

    public function returnblocksAction()
    {
        $response = [
            'success' => true,
            'errors' => [],
            'content' => []
        ];

        $blocks = json_decode($this->getRequest()->getParam('blocks'));
        foreach ($blocks as $block) {
            // echo '<pre>';
            // var_dump($this->getLayout()->createBlock($block->type)->setTemplate($block->template)->toHtml());
            // var_dump($block->type);
            // echo '<pre>';
            // continue;
            // $block->block_id
            // echo $this->getLayout()->createBlock('core/template')->setTemplate('dde/newproduct.phtml')->toHtml();

            $response['content'][] = [
                'key' => $block->id,
                'content' => $this->getLayout()->createBlock($block->type)->setTemplate($block->template)->toHtml()
            ];
        }
        // Zend_Debug::dump($response);
        // exit;

        $this->getResponse()->clearHeaders();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        // echo '<p>sjmparts</p>';
    }
}
