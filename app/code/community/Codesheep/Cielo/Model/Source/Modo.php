<?php

class Codesheep_Cielo_Model_Source_Modo
{
    public function toOptionArray()
    {
        return array(
            array('value' => '0', 'label' => 'Homologação'),
            array('value' => '1', 'label' => 'Produção')
        );
    }
}
