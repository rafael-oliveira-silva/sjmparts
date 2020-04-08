<?php

class Codesheep_Cielo_Model_Source_Parcelamento
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 2,
                'label' => 'Loja'
            ),
            array(
                'value' => 3,
                'label' => 'Administradora'
            ),
        );
    }
}
