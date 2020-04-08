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
class DDE_Clearsale_Model_Clearsale extends Mage_Core_Model_Abstract{
	protected $orderId = NULL;
	protected $fingerPrint = NULL;
	
	/**
	 * Generate Finger Print authentication
	 * which is required by ClearSale
	 *
	 * @method __construct
	 *
	 * @return parent::__construct()
	 */
	public function __construct(){
		$this->fingerPrint = sha1(microtime().rand(0,14012013));
		
		return parent::__construct();
	}
	
	/**
	 * Get finger print
	 *
	 * @method getFingerPrint
	 *
	 * @return fingerPrint
	 */
	public function getFingerPrint(){
		return Mage::getModel('core/session')->getFingerPrint();
	}
	
	/**
	 * Generate finger print and set it to current session
	 *
	 * @method generateFingerPrint
	 *
	 * @return fingerPrint
	 */
	public function generateFingerPrint($new = FALSE){
		$session = Mage::getModel('core/session');
		
		if( $new ){
			$this->fingerPrint = sha1(microtime().rand(0,14012013));
			$session->unsFingerPrint();
		}
		
		if( empty($this->fingerPrint) ) $this->fingerPrint = sha1(microtime().rand(0,14012013));
		
		// $fingerPrint = $this->fingerPrint;
		
		$session->setFingerPrint($this->fingerPrint);
		
		return $session->getFingerPrint();
	}
	
	/**
	 * Creates Finger Print local URL
	 *
	 * @method getFingerPrintUrl
	 *
	 * @param uri URI to attach
	 *
	 * @return url Formed URL
	 */
	public function getFingerPrintUrl($uri = NULL){
		if( empty($uri) ) return FALSE;
		$uri = ltrim($uri, '/');
		
		$url = Mage::getUrl('clearsale/index/redirectFingerPrint', array('_forced_secure'=>TRUE)).$uri;
		
		return $url;
	}
	
	/**
	 * Construct XML for send request
	 *
	 * @method generateXml
	 *
	 * @param order Order Varien Object with full data
	 *
	 * @return $xml Formed XML schema
	 */
	public function generateXml($order = NULL){
		if( empty($order) ) return FALSE;
		
		$customer		 = Mage::getModel('customer/customer')->load($order->getCustomerId());
		$billing		 = $order->getBillingAddress();
		$shipping		 = $order->getShippingAddress();
		$billingPhone	 = array( 'ddd' => str_replace('_', '0', substr($billing->getTelephone(), 1, 2)), 'number' => str_replace(array('-','_'),'',substr($billing->getTelephone(), 4)) );
		$shippingPhone	 = array( 'ddd' => str_replace('_', '0', substr($shipping->getTelephone(), 1, 2)), 'number' => str_replace(array('-','_'),'',substr($shipping->getTelephone(), 4)) );
		$billingMobile	 = array( 'ddd' => str_replace('_', '0', substr($billing->getFax(), 1, 2)), 'number' => str_replace(array('-','_'),'',substr($billing->getFax(), 4)) );
		$shippingMobile	 = array( 'ddd' => str_replace('_', '0', substr($shipping->getFax(), 1, 2)), 'number' => str_replace(array('-','_'),'',substr($shipping->getFax(), 4)) );
		$items 			 = $order->getAllVisibleItems();
		$itemsGrandTotal = 0;
		$itemsQty		 = 0;
		$customerType	 = count(str_replace(array('.','-','/'), '', $order->getCustomerTaxvat())) > 11 ? '2' : '1';
		
		foreach( $items as $item ){
			$itemsGrandTotal += round((round($item->getPrice(), 2, PHP_ROUND_HALF_DOWN) * $item->getQtyOrdered()) - round($item->getDiscountAmount(), 2, PHP_ROUND_HALF_DOWN), 2, PHP_ROUND_HALF_DOWN);
			$itemsQty		 += $item->getQtyOrdered();
		}
		
		$xml = new SimpleXMLElement('<ClearSale />');
		
		// Orders
		$_orders = $xml->addChild('Orders');
		
		// Order
		$_order = $_orders->addChild('Order');
		$_order->addChild('ID', $order->getIncrementId());
		
		// Order - Finger Print
		$_fingerPrint = $_order->addChild('FingerPrint');
		$_fingerPrint->addChild('SessionID', Mage::getModel('core/session')->getFingerPrint());
		
		$_order->addChild('Date', str_replace(' ', 'T', $order->getCreatedAt()));
		$_order->addChild('Email', $order->getCustomerEmail());
		// $_order->addChild('B2B_B2C', '');
		$_order->addChild('ShippingPrice', $order->getShippingAmount() - $order->getShippingDiscountAmount());
		$_order->addChild('TotalItems', number_format($itemsGrandTotal, 4, '.', ''));
		$_order->addChild('TotalOrder', number_format($order->getGrandTotal(), 4, '.', ''));
		$_order->addChild('QtyItems', $itemsQty);
		$_order->addChild('QtyPaymentTypes', 1);
		$_order->addChild('IP', $order->getRemoteIp());
		
		// Billing
		$_billing = $_order->addChild('BillingData');
		$_billing->addChild('ID', $order->getCustomerId());
		$_billing->addChild('Type', $customerType);
		$_billing->addChild('LegalDocument1', str_replace(array('.','-','/'), '', $order->getCustomerTaxvat()));
		$_billing->addChild('LegalDocument2', str_replace(array('.','-','/'), '', $customer->getRgie().''));
		$_billing->addChild('Name', $order->getCustomerName());
		// $_billing->addChild('BirthDate', $order->getCustomerDob().'');
		
		// Billing - Address
		$_billingAddress = $_billing->addChild('Address');
		$_billingAddress->addChild('Street', $billing->getStreet(1));
		$_billingAddress->addChild('Number', $billing->getStreet(2));
		$_billingAddress->addChild('County', $billing->getStreet(4));
		$_billingAddress->addChild('City', $billing->getCity());
		$_billingAddress->addChild('State', $billing->getRegionCode());
		$_billingAddress->addChild('ZipCode', str_replace('-','',$billing->getPostcode()));
		
		// Billing - Phones
		$_billingPhones = $_billing->addChild('Phones');
		
		// Billing - Phone
		$_billingPhone = $_billingPhones->addChild('Phone');
		$_billingPhone->addChild('Type', '1');
		$_billingPhone->addChild('DDD', $billingPhone['ddd']);
		$_billingPhone->addChild('Number', str_pad($billingPhone['number'], 8, '0', STR_PAD_LEFT));
		
		// Billing - Mobile
		if( !empty($billingMobile['ddd']) ){
			$_billingMobile = $_billingPhones->addChild('Phone');
			$_billingMobile->addChild('Type', '6');
			$_billingMobile->addChild('DDD', $billingMobile['ddd']);
			$_billingMobile->addChild('Number', str_pad($billingMobile['number'], 8, '0', STR_PAD_LEFT));
		}
		
		// Shipping
		$_shipping = $_order->addChild('ShippingData');
		$_shipping->addChild('ID', $order->getCustomerId());
		$_shipping->addChild('Type', $customerType);
		$_shipping->addChild('LegalDocument1', str_replace(array('.','-','/'), '', $order->getCustomerTaxvat()));
		$_shipping->addChild('LegalDocument2', str_replace(array('.','-','/'), '', $customer->getRgie().''));
		$_shipping->addChild('Name', $order->getCustomerName());
		// $_shipping->addChild('BirthDate', $order->getCustomerDob().'');
		
		// Shipping - Address
		$_shippingAddress = $_shipping->addChild('Address');
		$_shippingAddress->addChild('Street', $shipping->getStreet(1));
		$_shippingAddress->addChild('Number', $shipping->getStreet(2));
		$_shippingAddress->addChild('County', $shipping->getStreet(4));
		$_shippingAddress->addChild('City', $shipping->getCity());
		$_shippingAddress->addChild('State', $shipping->getRegionCode());
		$_shippingAddress->addChild('ZipCode', str_replace('-','',$shipping->getPostcode()));
		
		// Shipping - Phones
		$_shippingPhones = $_shipping->addChild('Phones');
		
		// Shipping - Phone
		$_shippingPhone = $_shippingPhones->addChild('Phone');
		$_shippingPhone->addChild('Type', '1');
		$_shippingPhone->addChild('DDD', $shippingPhone['ddd']);
		$_shippingPhone->addChild('Number', str_pad($shippingPhone['number'], 8, '0', STR_PAD_LEFT));
		
		// Shipping - Mobile
		if( !empty($shippingMobile['ddd']) ){
			$_shippingMobile = $_shippingPhones->addChild('Phone');
			$_shippingMobile->addChild('Type', '6');
			$_shippingMobile->addChild('DDD', $shippingMobile['ddd']);
			$_shippingMobile->addChild('Number', str_pad($shippingMobile['number'], 8, '0', STR_PAD_LEFT));
		}
		
		// Payments
		$_payments = $_order->addChild('Payments');
		
		// Payment
		$_payment = $_payments->addChild('Payment');
		$_payment->addChild('Date', str_replace(' ', 'T', $order->getCreatedAt()));
		$_payment->addChild('Amount', number_format($order->getGrandTotal(), 4, '.', ''));
		$_payment->addChild('PaymentTypeID', Mage::helper('clearsale')->getPaymentTypeId($order->getPayment()->getMethod()));
		
		// Items
		$_items = $_order->addChild('Items');
		
		// Item
		foreach( $items as $item ){
			$itemName = str_replace(' ', '-', $item->getName());
			$itemName = preg_replace('/[^A-Za-z0-9\-]/', '', $itemName);
			$itemName = str_replace('-', ' ', $itemName);

			$_item = $_items->addChild('Item');
			$_item->addChild('ID', $item->getProductId());
			$_item->addChild('Name', $itemName);
			$_item->addChild('ItemValue', $item->getPrice() - $item->getDiscountAmount());
			$_item->addChild('Qty', (int) $item->getQtyOrdered()); 
		}
		
		return $xml->asXML();
	}
}