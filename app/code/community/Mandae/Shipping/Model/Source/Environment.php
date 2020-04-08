<?php
/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @copyright  Copyright (c) 2017 Bizcommerce
 */
class Mandae_Shipping_Model_Source_Environment
{
    public function toOptionArray()
    {
        /** @var Mandae_Shipping_Helper_Data $_helper */
        $_helper = Mage::helper('mandae');

        $options = array(
            'sandbox' => $_helper->__('Sandbox'),
            'live' => $_helper->__('Live')
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