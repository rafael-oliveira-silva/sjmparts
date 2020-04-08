<?php
/**
 * 18digital
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPL 3.0)
 * that is bundled with this package in the file LICENSE_GPL.txt
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to suporte@18digital.com.br so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * You can edit, copy, distribute and change this file, however, no information
 * about it's author, company, owner or any legal information can be changed,
 * erase or edited in no circumstances.
 *
 * @category      DDE
 * @package       DDE_ClearSale
 * @author		  Samir J M Araujo
 * @authorEmail   samir.araujo@18digital.com.br
 * @company	 	  18digital
 * @copyright     Copyright (c) 2013 18digital (http://18digital.com.br)
 * @version		  1.0.0
 * @license       GPL-3.0  GNU General Public License (GPL 3.0)
 * @licenseUrl    http://opensource.org/licenses/gpl-3.0.html
 */

class DDE_Clearsale_Model_Observer
{
    /**
     * Send order data to ClearSale
     *
     * @param Varien_Event_Observer $observer observer object
     *
     * @return boolean
     */
    public function sendOrder(Varien_Event_Observer $observer)
    {
        // $_order = $observer->getEvent()->getOrder();
        // $order = Mage::getModel('sales/order')->load($_order->getId());
        
        $order = $observer->getEvent()->getOrder();
        
        // Mage::log('Cielo debug Status: '.$order->getStatus);
        // Mage::log('Cielo debug Code: '.$order->getPayment()->getMethodInstance()->getCode());
        // Mage::log('Cielo debug Methods: '.json_encode(get_class_methods($order)));
        $isValidPaymentMethod = in_array($order->getPayment()->getMethodInstance()->getCode(), ['rede_adquirencia', 'cielo', 'cielo_cc']);
        
        if ($isValidPaymentMethod && $order->getStatus() != 'canceled') {
            // @TODO: Check model call
            $clearSale = Mage::getModel('clearsale/clearsale');
            $xml = $clearSale->generateXml($order);
            // Mage::log('ClearSale Extension XML Debug: '.$xml);
            
            $client = new Varien_Http_Client('http://www.clearsale.com.br/integracaov2/service.asmx/SendOrders');
            $client->setMethod(Varien_Http_Client::POST)
                   ->setParameterPost('entityCode', '7E023DDC-0AFF-492C-82CC-7FB206106DF0')
                   ->setParameterPost('xml', $xml);
            
            try {
                $response = $client->request();
            } catch (Exception $e) {
                Mage::log($e->getMessage());
            }
        }
        
        return $this;
    }
}
