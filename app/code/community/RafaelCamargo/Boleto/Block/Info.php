<?php

class RafaelCamargo_Boleto_Block_Info extends Mage_Payment_Block_Info_Ccsave{
	// @TODO: Write function description
	protected function _construct(){
		parent::_construct();
		$this->setTemplate('boleto/info.phtml');
	}
	
	// @TODO: Write function description
	public function getOrder(){
		$order = Mage::registry('current_order');
		
		$info = $this->getInfo();
		
		if (!$order) {
			if ($this->getInfo() instanceof Mage_Sales_Model_Order_Payment) {
				$order = $this->getInfo()->getOrder();
			}
		}
		
		return $order;
	}
}