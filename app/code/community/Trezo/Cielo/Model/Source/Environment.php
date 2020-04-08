<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <andre@trezo.com.br>
 *
 */
class Trezo_Cielo_Model_Source_Environment
{
    const SANDBOX = 'development';
    const PRODUCTION = 'production';

    public function toOptionArray()
    {
        return
            array(
                self::SANDBOX   => Mage::helper('trezo_cielo')->__('Sandbox'),
                self::PRODUCTION    => Mage::helper('trezo_cielo')->__('Production'),
            );
    }
}
