<?php

class RafaelCamargo_Boleto_Model_Observer{
	public function changeOrderStatus($event){
		$order = $event->getOrder();
		$paymentMethod = $order->getPayment()->getMethodInstance()->getCode();
		
		if( $paymentMethod == 'boleto_bancario' ) $order->setStatus('pending_billet')->save();
		
		return $this;
	}
}