<?php
// ini_set('display_errors', TRUE);
class DDE_Clearsale_IndexController extends Mage_Core_Controller_Front_Action{
	public function sendOrderAction(){
	    exit;

		$order 			 = Mage::getModel('sales/order')->loadByIncrementId(100002310);
		$customer		 = Mage::getModel('customer/customer')->load($order->getCustomerId());
		$billing		 = $order->getBillingAddress();
		$shipping		 = $order->getShippingAddress();
		$billingPhone	 = array( 'ddd' => substr($billing->getTelephone(), 1, 2), 'number' => str_replace('-','',substr($billing->getTelephone(), 4)) );
		$shippingPhone	 = array( 'ddd' => substr($shipping->getTelephone(), 1, 2), 'number' => str_replace('-','',substr($shipping->getTelephone(), 4)) );
		$billingMobile	 = array( 'ddd' => substr($billing->getFax(), 1, 2), 'number' => str_replace('-','',substr($billing->getFax(), 4)) );
		$shippingMobile	 = array( 'ddd' => substr($shipping->getFax(), 1, 2), 'number' => str_replace('-','',substr($shipping->getFax(), 4)) );
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
		$_fingerPrint->addChild('SessionID', '8asdf5adsz4');
		
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
		$_billingAddress->addChild('ZipCode', $billing->getPostcode());
		
		// Billing - Phones
		$_billingPhones = $_billing->addChild('Phones');
		
		// Billing - Phone
		$_billingPhone = $_billingPhones->addChild('Phone');
		$_billingPhone->addChild('Type', '1');
		$_billingPhone->addChild('DDD', $billingPhone['ddd']);
		$_billingPhone->addChild('Number', $billingPhone['number']);
		
		// Billing - Mobile
		if( !empty($billingPhone['ddd']) ){
			$_billingMobile = $_billingPhones->addChild('Phone');
			$_billingMobile->addChild('Type', '6');
			$_billingMobile->addChild('DDD', $billingMobile['ddd']);
			$_billingMobile->addChild('Number', $billingMobile['number']);
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
		$_shippingAddress->addChild('ZipCode', $shipping->getPostcode());
		
		// Shipping - Phones
		$_shippingPhones = $_shipping->addChild('Phones');
		
		// Shipping - Phone
		$_shippingPhone = $_shippingPhones->addChild('Phone');
		$_shippingPhone->addChild('Type', '1');
		$_shippingPhone->addChild('DDD', $shippingPhone['ddd']);
		$_shippingPhone->addChild('Number', $shippingPhone['number']);
		
		// Shipping - Mobile
		if( !empty($shippingPhone['ddd']) ){
			$_shippingMobile = $_shippingPhones->addChild('Phone');
			$_shippingMobile->addChild('Type', '6');
			$_shippingMobile->addChild('DDD', $shippingMobile['ddd']);
			$_shippingMobile->addChild('Number', $shippingMobile['number']);
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
			$_item = $_items->addChild('Item');
			$_item->addChild('ID', $item->getProductId());
			$_item->addChild('Name', $item->getName());
			$_item->addChild('ItemValue', $item->getPrice() - $item->getDiscountAmount());
			$_item->addChild('Qty', (int) $item->getQtyOrdered()); 
		}
		
		// echo $xml->asXML();
		// exit;
		
		$client = new Varien_Http_Client('http://www.clearsale.com.br/integracaov2/service.asmx/SendOrders');
		$client->setMethod(Varien_Http_Client::POST)
			   ->setParameterPost('entityCode', '7E023DDC-0AFF-492C-82CC-7FB206106DF0')
			   ->setParameterPost('xml', $xml->asXML());
		
		try{
			$response = $client->request();
			echo $response->getBody();
		}catch( Exception $e ){
			Zend_Debug::dump( $e->getMessage() );
		}
	}
	
	public function getOrderStatusAction(){
		$client = new Varien_Http_Client('http://www.clearsale.com.br/integracaov2/service.asmx/SendOrders');
		$client->setMethod(Varien_Http_Client::POST)
			   ->setParameterPost('entityCode', '7E023DDC-0AFF-492C-82CC-7FB206106DF0')
			   ->setParameterPost('orderID', '100002142');
		
		try{
			$response = $client->request();
			echo $response->getBody();
		}catch( Exception $e ){
			Zend_Debug::dump( $e->getMessage() );
		}
	}
	
	/*public function testModelAction(){
		Zend_Debug::dump( Mage::getModel('sales/order')->loadByIncrementId(100002129)->getBillingAddress()->getStreet() );
	}*/
	
	public function redirectFingerPrintAction(){
		$request = $this->getRequest();
		$uri = str_replace( '/clearsale/index/redirectFingerPrint/', '', $request->getRequestUri() );
		
		// echo Mage::getUrl('clearsale/index/redirectFingerPrint', array('_forced_secure'=>TRUE));
		
		$this->_redirectUrl('https://h.online-metrix.net/'.$uri);
	}
	
	/**
	 * Send order data to ClearSale
	 *
	 * @param Varien_Event_Observer $observer observer object
	 *
	 * @return boolean
	 */
	public function resendOrderAction(){
		$order = Mage::getModel('sales/order')->loadByIncrementId(100010309);
		
		// @TODO: Check model call
		$clearSale = Mage::getModel('clearsale/clearsale');
		$xml = $clearSale->generateXml($order);
		// echo '<pre>';
		// echo $xml;
		// exit;
		// Mage::log('ClearSale Extension XML Debug: '.$xml);
		
		$client = new Varien_Http_Client('http://www.clearsale.com.br/integracaov2/service.asmx/SendOrders');
		$client->setMethod(Varien_Http_Client::POST)
			   ->setParameterPost('entityCode', '7E023DDC-0AFF-492C-82CC-7FB206106DF0')
			   ->setParameterPost('xml', $xml);
		
		try{
			$response = $client->request();
			echo $response->getBody();
		}catch( Exception $e ){
			echo $e->getMessage();
			Mage::log( $e->getMessage() );
		}
		
		return $this;
	}
	
	public function checkOrderStatusAction(){
		exit;
		$helper = Mage::helper('clearsale');
		$order = Mage::getModel('sales/order')->loadByIncrementId(100002180);
		
		$client = new Varien_Http_Client('http://www.clearsale.com.br/integracaov2/service.asmx/GetOrderStatus');
		$client->setMethod(Varien_Http_Client::POST)
			   ->setParameterPost('entityCode', '7E023DDC-0AFF-492C-82CC-7FB206106DF0')
			   ->setParameterPost('orderID', $order->getIncrementId());
		
		try{
			$response = $client->request();
			// echo $response->getRawBody();
			$_xml = new SimpleXMLElement($response->getBody());
			echo $_xml;
			// echo $response->getBody();
			// echo $response->getRawBody();
			// Zend_Debug::dump( new SimpleXMLElement($_xml) );
		}catch( Exception $e ){
			echo $e->getMessage();
			Mage::log($e->getMessage());
			// Zend_Debug::dump( $e->getMessage() );
		}
	}

	public function generatecsvAction(){
		$collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');

		$finalCollection = Mage::getModel('catalog/product')->getCollection()
			->addAttributeToSelect('*');
		
		$finalCollection->getSelect()
			->joinLeft(
				array('sfoi' => $collection->getResource()->getTable('sales/order_item')),
	            'e.entity_id = sfoi.product_id',
	            array('qty_ordered' => 'SUM(sfoi.qty_ordered)')
			)
			->group('e.entity_id')
            ->order('qty_ordered ' . 'DESC');

        $content = '';
        
        $io = new Varien_Io_File();
        $path = Mage::getBaseDir('var') . DS . 'export' . DS;
        $name = md5(microtime());
        $file = $path . DS . $name . '.csv';
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $path));
        $io->streamOpen($file, 'w+');
        $io->streamLock(true);
        $io->streamWriteCsv(array('ID', 'Nome do Produto', 'Quantidade Vendida', 'Em Estoque?'));
        foreach( $finalCollection as $product ){
        	$content = array(
        		'id' => $product->getId(),
        		'product_name' => $product->getName(),
        		'sold_qty' => $product->getQtyOrdered(),
        		'is_in_stock' => $product->isInStock() ? 'Sim' : 'Não'
        	);
        	$io->streamWriteCsv($content);
        }

        $this->_prepareDownloadResponse('export_products_status_13-05-15_00.csv', array(
                    'type'  => 'filename',
                    'value' => $file,
                    'rm'    => true // can delete file after use
                ));
	}
}