<?php

/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @author     Bruno Rodrigues Ferreira
 * @copyright  Copyright (c) 2017 Bizcommerce
 * @copyright  Copyright (c) 2018 Mandaê
 */
class Mandae_Shipping_Model_Source_Services
{
    public function toOptionArray()
    {
        /** @var Mandae_Shipping_Helper_Data $_helper */
        $_helper = Mage::helper('mandae');

        $options = array(
            'rapido' => $_helper->__('Fast'),
            'economico' => $_helper->__('Economy'),
            'super-rapido' => $_helper->__('Express'),
            'same-day' => $_helper->__('Sameday')
        );

        $result = array();
        foreach ($options as $key => $option) {
            $result[] = array(
                'value' => $key,
                'label' => $option
            );
        }

        return $result;
    }
}