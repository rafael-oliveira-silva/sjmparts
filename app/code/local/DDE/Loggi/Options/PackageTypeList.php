<?php
/**
 *
 */
class DDE_Loggi_Options_PackageTypeList
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array(
                "value" => "document",
                "label" => Mage::helper("adminhtml")->__("Documento")
            ),
            array(
                "value" => "box",
                "label" =>
                    Mage::helper("adminhtml")->__(
                        "Pequeno (15cm X 15cm X 15cm)"
                    )
            ),
            array(
                "value" => "medium",
                "label" =>
                    Mage::helper("adminhtml")->__("Médio (20cm X 20xm X 20cm)")
            ),
            array(
                "value" => "large_box",
                "label" =>
                    Mage::helper("adminhtml")->__("Grande (42cm X 44cm X 32cm)")
            )
        );
    }

    public function getAllOptions()
    {
        return self::toOptionArray();
    }
}
