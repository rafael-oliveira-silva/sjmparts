<?php

class DDE_Jadlog_Model_Source_Modalidades{
	public function toOptionArray(){
		return array(
			array( 'value' => 'jadlogmethod0',  'label' => Mage::helper('jadlogmethod')->__('Expresso (Standard)'), 'alias' => 'Expresso', 		    'column' => 'standard' ),
			array( 'value' => 'jadlogmethod3',  'label' => Mage::helper('jadlogmethod')->__('.package'),            'alias' => '.package', 		    'column' => 'package' ),
			array( 'value' => 'jadlogmethod4',  'label' => Mage::helper('jadlogmethod')->__('Rodoviario'),          'alias' => 'Rodovi&aacute;rio', 'column' => 'rodoviario' ),
			array( 'value' => 'jadlogmethod5',  'label' => Mage::helper('jadlogmethod')->__('Economico'),           'alias' => 'Econ&ocirc;mico',   'column' => 'economico' ),
			array( 'value' => 'jadlogmethod6',  'label' => Mage::helper('jadlogmethod')->__('Doc'),                 'alias' => 'Doc', 			    'column' => 'doc' ),
			array( 'value' => 'jadlogmethod7',  'label' => Mage::helper('jadlogmethod')->__('Corporate'),           'alias' => 'Corporate', 		'column' => 'corporate' ),
			array( 'value' => 'jadlogmethod9',  'label' => Mage::helper('jadlogmethod')->__('.com'),                'alias' => '.com', 			    'column' => 'com' ),
			array( 'value' => 'jadlogmethod10', 'label' => Mage::helper('jadlogmethod')->__('Internacional'),       'alias' => 'Internacional', 	'column' => 'internacional' ),
			array( 'value' => 'jadlogmethod12', 'label' => Mage::helper('jadlogmethod')->__('Cargo'),               'alias' => 'Cargo',			    'column' => 'cargo' ),
			array( 'value' => 'jadlogmethod14', 'label' => Mage::helper('jadlogmethod')->__('Emergencia'),          'alias' => 'Emerg&ecirc;ncia',  'column' => 'emergencia' )
		);
	}
}