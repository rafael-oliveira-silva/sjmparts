<?php
/**
 * @category    Trezo
 * @package     Trezo_Cielo
 * @copyright   Copyright (c) http://www.trezo.com.br
 * @author      André Felipe <contato@trezo.com.br>
 *
 */
class Trezo_Cielo_Model_Source_Address
{
    public function toOptionArray()
    {
        if (!$addressAttributes = Mage::registry('address_attributes')) {
            $addressAttributes = array(
                array('value' => 0, 'label' => Mage::helper('trezo_cielo')->__('--Please Select--')),
                array('value' => 1, 'label' => Mage::helper('trezo_cielo')->__('Street 1')),
                array('value' => 2, 'label' => Mage::helper('trezo_cielo')->__('Street 2')),
                array('value' => 3, 'label' => Mage::helper('trezo_cielo')->__('Street 3')),
                array('value' => 4, 'label' => Mage::helper('trezo_cielo')->__('Street 4'))
            );

            foreach (Mage::helper('customer/address')->getAttributes() as $attribute) {
                $addressAttributes[] = array(
                    'value' => $attribute->getAttributeCode(),
                    'label' => $attribute->getFrontendLabel()
                );
            }

            Mage::register('address_attributes', $addressAttributes);
        }

        return $addressAttributes;
    }
}
