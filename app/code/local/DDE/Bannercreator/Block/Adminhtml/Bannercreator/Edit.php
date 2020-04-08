<?php
	
class DDE_Bannercreator_Block_Adminhtml_Bannercreator_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "bannercreator";
				$this->_controller = "adminhtml_bannercreator";
				$this->_updateButton("save", "label", Mage::helper("bannercreator")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("bannercreator")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("bannercreator")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("bannercreator_data") && Mage::registry("bannercreator_data")->getId() ){

				    return Mage::helper("bannercreator")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("bannercreator_data")->getId()));

				} 
				else{

				     return Mage::helper("bannercreator")->__("Add Item");

				}
		}
}