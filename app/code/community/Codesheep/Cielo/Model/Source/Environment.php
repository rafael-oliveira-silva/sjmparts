<?php

class Codesheep_Cielo_Model_Source_Environment
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 0,
                'label' => 'Test'
            ),
            array(
                'value' => 1,
                'label' => 'Production'
            ),
        );
    }
}
