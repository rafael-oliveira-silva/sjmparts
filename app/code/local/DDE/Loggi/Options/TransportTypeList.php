<?php
/**
 *
 */
class DDE_Loggi_Options_TransportTypeList
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array(
                "value" => "bicicleta",
                "label" => Mage::helper("adminhtml")->__("Bicicleta")
            ),
            array(
                "value" => "moto",
                "label" => Mage::helper("adminhtml")->__("Moto")
            ),
            array(
                "value" => "carro",
                "label" => Mage::helper("adminhtml")->__("Carro")
            ),
            array(
                "value" => "van",
                "label" => Mage::helper("adminhtml")->__("Van")
            )
        );
    }

    public function getAllOptions()
    {
        return self::toOptionArray();
    }
}
