<?php

class DDE_Jadlog_Model_Source_Seguros{
	public function toOptionArray(){
		return array(
			array( 'value' => 'N', 'label' => 'Normal' ),
			array( 'value' => 'A', 'label' => 'Apolice propria' )
		);
	}
}