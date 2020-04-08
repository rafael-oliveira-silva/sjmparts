<?php

class DDE_Jadlog_Model_Cep extends Mage_Core_Model_Abstract{
	public function _construct(){
		parent::_construct();
		$this->_init('jadlogmethod/cep');
	}
	
	public function loadByCep($cep){
		$id = $this->_getResource()->getIdByCep($cep);
		return $this->load($id);
	}
}