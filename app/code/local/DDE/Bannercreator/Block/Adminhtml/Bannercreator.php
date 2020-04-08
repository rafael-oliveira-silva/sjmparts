<?php


class DDE_Bannercreator_Block_Adminhtml_Bannercreator extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_bannercreator";
	$this->_blockGroup = "bannercreator";
	$this->_headerText = Mage::helper("bannercreator")->__("Bannercreator Manager");
	$this->_addButtonLabel = Mage::helper("bannercreator")->__("Add New Item");
	parent::__construct();
	
	}

}