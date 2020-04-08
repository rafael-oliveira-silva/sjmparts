<?php

class DDE_Jadlog_Model_Mysql4_Cep extends Mage_Core_Model_Mysql4_Abstract{
	public function _construct(){
		$this->_init('jadlogmethod/cep', 'id');
	}
	
	public function getIdByCep($cep){
		$cep = str_replace( '-', '', $cep );
		/*$select = $this->_getReadAdapter()
		->select()
		->from($this->getTable('jadlogmethod/cep'))
		->where('cep_inicial <= ? AND cep_final >= ?', $cep);*/
		
		$sql = 'SELECT * FROM '.Mage::getSingleton('core/resource')->getTableName('jadlogmethod/cep').' WHERE cep_inicial <= '.$cep.' && cep_final >= '.$cep;
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		if( $id = $connection->fetchOne($sql) ) return $id;
		else return NULL;
		
		
		// if( $id = $this->_getReadAdapter()->fetchOne($select) ) return $id;		
		// return NULL;
	}
}