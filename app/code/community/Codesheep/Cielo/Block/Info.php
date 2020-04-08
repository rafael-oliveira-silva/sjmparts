<?php

class Codesheep_Cielo_Block_Info extends Mage_Payment_Block_Info_Ccsave
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cielo/info.phtml');
    }
	
	/**
     * Retrieve current order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        $order = Mage::registry('current_order');
		
		$info = $this->getInfo();
		
		if (!$order) {
			if ($this->getInfo() instanceof Mage_Sales_Model_Order_Payment) {
				$order = $this->getInfo()->getOrder();
			}
		}
		
		return($order);
    }
}