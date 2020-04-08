<?php

class DDE_Jadlog_Model_Mysql4_Cep_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract{
	public function _construct(){
		parent::_construct();
		$this->_init('jadlogmethod/cep');
	}
}