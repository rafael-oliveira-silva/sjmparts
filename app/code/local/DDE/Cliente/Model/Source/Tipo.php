<?php
/**
 * DDE_Cliente extension for Magento
 *
 * Source class to define types of customer
 *
 * @category    DDE
 * @package     DDE_Cliente
 * @version     1.1.0
 * @author      Samir J M Araujo
 * @authorEmail samir.araujo@18digital.com.br
 * @support     suporte@18digital.com.br
 */
class DDE_Cliente_Model_Source_Tipo extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions(){
		
		if ($this->_options === null){
			$this->_options = array();
			
			$this->_options[] = array(
                    'value' => 1,
                    'label' => 'Pessoa física'
			);
			
			$this->_options[] = array(
                    'value' => 2,
                    'label' => 'Pessoa jurídica'
			);
		}
		
		return $this->_options;
	}
}