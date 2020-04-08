<?php
/**
 *
 */
class DDE_Loggi_Options_CitiesList
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array(
                "value" => "1",
                "label" => Mage::helper("adminhtml")->__("São Paulo")
            ),
            array(
                "value" => "2",
                "label" => Mage::helper("adminhtml")->__("Rio de Janeiro")
            ),
            array(
                "value" => "3",
                "label" => Mage::helper("adminhtml")->__("Belo Horizonte")
            ),
            array(
                "value" => "4",
                "label" => Mage::helper("adminhtml")->__("Curitiba")
            ),
            array(
                "value" => "5",
                "label" => Mage::helper("adminhtml")->__("Porto Alegre")
            )
        );
    }

    public function getAllOptions()
    {
        return self::toOptionArray();
    }
}
