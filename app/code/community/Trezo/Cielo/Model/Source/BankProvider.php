<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <andre@trezo.com.br>
 *
 */


class Trezo_Cielo_Model_Source_BankProvider
{
    public function toOptionArray()
    {
        return array(
            array('value' => Cielo_Api_Payment::PROVIDER_SIMULADO,        'label'=>Mage::helper('adminhtml')->__('Simulado')),
            array('value' => Cielo_Api_Payment::PROVIDER_BANCO_DO_BRASIL, 'label'=>Mage::helper('adminhtml')->__('Banco do Brasil')),
            array('value' => Cielo_Api_Payment::PROVIDER_BRADESCO,        'label'=>Mage::helper('adminhtml')->__('Bradesco')),
            array('value' => 'BancodoBrasil2',                            'label'=>Mage::helper('adminhtml')->__('Banco do Brasil Registrado')),
            array('value' => 'Bradesco2',                             'label'=>Mage::helper('adminhtml')->__('Bradesco Registrado'))
        );
    }
}
