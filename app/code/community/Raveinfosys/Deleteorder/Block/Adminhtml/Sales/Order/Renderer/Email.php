<?php class Raveinfosys_Deleteorder_Block_Adminhtml_Sales_Order_Renderer_Email extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	public function render(Varien_Object $row) {
		// $options = $this->getColumn()->getOptions();
		// $value =  $row->getData($this->getColumn()->getIndex());
		$id = $row->getData('customer_id');
		if( !empty($id) ){
			return Mage::getModel('customer/customer')->load($id)->getEmail();
		}
	}
}
