<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <andre@trezo.com.br>
 *
 */
class Trezo_Cielo_Model_Source_Dctypes
{
    /**
     * Bandeira do cartão (Visa / Master / Amex / Elo / Aura / JCB / Diners / Discover).
     * @return array return brands of credit card
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'VI',
                'label' => Mage::helper('adminhtml')->__('Visa')
            ],
            [
                'value' => 'MC',
                'label' => Mage::helper('adminhtml')->__('MasterCard')
            ]
        ];
    }

    public function getCcTypeForLabel($label)
    {
        $ccTypes = $this->toOptionArray();
        foreach ($ccTypes as $cc) {
            if ($cc['label'] == $label) {
                return $cc['value'];
            }
        }
    }

    public function getCcTypeLabelForValue($value)
    {
        $ccTypes = $this->toOptionArray();
        foreach ($ccTypes as $cc) {
            if ($cc['value'] == $value) {
                return $cc['label'];
            }
        }
    }
}
