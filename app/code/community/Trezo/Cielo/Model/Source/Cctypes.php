<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <andre@trezo.com.br>
 *
 */
class Trezo_Cielo_Model_Source_Cctypes
{
    /**
     * Bandeira do cartão (Visa / Master / Amex / Elo / Aura / JCB / Diners / Discover/ Hipercard).
     * @return array return brands of credit card
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'VI', 'label'=>Mage::helper('adminhtml')->__('Visa')),
            array('value' => 'MC', 'label'=>Mage::helper('adminhtml')->__('MasterCard')),
            array('value' => 'AE', 'label'=>Mage::helper('adminhtml')->__('American Express')),
            array('value' => 'DN', 'label'=>Mage::helper('adminhtml')->__('Diners Club')),
            array('value' => 'EL', 'label'=>Mage::helper('adminhtml')->__('Elo')),
            array('value' => 'HI', 'label'=>Mage::helper('adminhtml')->__('Hipercard')),
            array('value' => 'AU', 'label'=>Mage::helper('adminhtml')->__('Aura')),
            array('value' => 'JC', 'label'=>Mage::helper('adminhtml')->__('JCB')),
            array('value' => 'DC', 'label'=>Mage::helper('adminhtml')->__('Discover'))
        );
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
