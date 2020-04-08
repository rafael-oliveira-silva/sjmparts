<?php
/**
 * @category    Trezo
 * @package     Trezo_Itaushopline
 * @copyright   Copyright (c) http://www.trezo.com.br
 * @author      André Felipe <contato@trezo.com.br>
 *
 */
class Trezo_Itaushopline_Model_System_Config_Source_Customer_Attributes
{
    public function toOptionArray()
    {
        if (!$attributeArray = Mage::registry('customer_attributes')) {
            $attributeArray     = array();
            $attributes = Mage::getModel('customer/customer')->getAttributes();
            $attributeArray[]   = Mage::helper('itaushopline')->__('--Please Select--');

            foreach ($attributes as $a) {
                foreach ($a->getEntityType()->getAttributeCodes() as $attributeName) {
                    $attributeArray[$attributeName] = $attributeName;
                }
                break;
            }

            Mage::register('customer_attributes', $attributeArray);
        }

        return $attributeArray;
    }
}
