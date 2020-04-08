<?php

class RafaelCamargo_Boleto_Model_Control extends Mage_Core_Model_Abstract{
	const XML_PATH_EMAIL_ADMIN_WARNING_EMAIL = 'payment/boleto_bancario/warning_email_template';
	const XML_PATH_EMAIL_ADMIN_NOTIFY_ORDER  = 'payment/boleto_bancario/notify_orders_template';
	
	// @TODO: Write function description
	public function expireOrders(){
		$enabled = Mage::getStoreConfig('payment/boleto_bancario/enable_auto_expire_order');
		
		if( !$enabled ) return $this;
		
		$orders = Mage::getSingleton('sales/order')->getCollection()
			->addAttributeToFilter('created_at', array('from' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/auto_expire_order_delay')).' 00:00:00', 'to' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/auto_expire_order_delay')).' 23:59:59' ))
			->addAttributeToFilter('status', array('eq' => 'pending_billet'));
		
		foreach( $orders as $order ) $order->setStatus('expired_billet')->save();
		
		return $this;
	}
	
	// @TODO: Write function description
	public function notifyOrders(){
		$enabled = Mage::getStoreConfig('payment/boleto_bancario/enable_notify_orders');
		
		if( !$enabled ) return $this;
		
		$orders = Mage::getSingleton('sales/order')->getCollection()
			->addAttributeToFilter('created_at', array('from' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/notify_orders_delay')).' 00:00:00', 'to' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/notify_orders_delay')).' 23:59:59'))
			->addAttributeToFilter('status', array('eq' => 'pending_billet'));
		
		$storeId = Mage::app()->getStore()->getId();
		$translate = Mage::getSingleton('core/translate');
		$translate->setTranslateInline(FALSE);
		$mailTemplate = Mage::getModel('core/email_template');
		$template = Mage::getStoreConfig(self::XML_PATH_EMAIL_ADMIN_NOTIFY_ORDER, $storeId);
		
		foreach( $orders as $order ){
			$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store' => $storeId))
				->sendTransactional(
					$template,
					Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY, $storeId),
					$order->getCustomerEmail(),
					$order->getCustomerName(),
					array('order'=>$order)
				);
		}
		
		$translate->setTranslateInline(TRUE);
		
		return $this;
	}
	
	// @TODO: Write function description
	public function notifyExpiredOrders(){
		$enabled = Mage::getStoreConfig('payment/boleto_bancario/enable_first_warning');
		
		if( !$enabled ) return $this;
		
		$orders = Mage::getSingleton('sales/order')->getCollection()
			->addAttributeToFilter('created_at', array('from' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/warning_email_delay')).' 00:00:00', 'to' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/warning_email_delay')).' 23:59:59' ))
			->addAttributeToFilter('status', array('eq' => 'expired_billet'));
		
		$storeId = Mage::app()->getStore()->getId();
		$translate = Mage::getSingleton('core/translate');
		$translate->setTranslateInline(FALSE);
		$mailTemplate = Mage::getModel('core/email_template');
		$template = Mage::getStoreConfig(self::XML_PATH_EMAIL_ADMIN_WARNING_EMAIL, $storeId);
		
		foreach( $orders as $order ){
			$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store' => $storeId))
				->sendTransactional(
					$template,
					Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY, $storeId),
					$order->getCustomerEmail(),
					$order->getCustomerName(),
					array('order'=>$order)
				);
		}
		
		$translate->setTranslateInline(TRUE);
		
		return $this;
	}
	
	// @TODO: Write function description
	public function cancelOrders(){
		$enabled = Mage::getStoreConfig('payment/boleto_bancario/enable_auto_cancel_order');
		
		if( !$enabled ) return $this;
		
		$orders = Mage::getSingleton('sales/order')->getCollection()
			->addAttributeToFilter('created_at', array('from' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/auto_cancel_order_delay')).' 00:00:00', 'to' => $this->getDate(Mage::getStoreConfig('payment/boleto_bancario/auto_cancel_order_delay')).' 23:59:59'))
			->addAttributeToFilter('status', array('eq' => 'expired_billet'));
		
		foreach( $orders as $order ) $order->cancel()->save();
		
		return $this;
	}
	
	// @TODO: Write function description
	protected function getDate($_subDate = 0, $format = 'Y-m-d'){
		
		$_date = new DateTime(date('Y-m-d'));
		$_date->sub(new DateInterval('P'.$_subDate.'D'));
		
		return date($format, $_date->format('U'));
	}
}