<?php

class Codesheep_Cielo_Model_Source_Empresa
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'clearsale', 'label' => 'Clear Sale'),
            array('value' => 'fcontrol', 'label' => 'F-Control')
        );
    }
}
