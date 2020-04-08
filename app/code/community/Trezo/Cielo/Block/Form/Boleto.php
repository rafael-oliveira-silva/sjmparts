<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Block_Form_Boleto extends Mage_Payment_Block_Form_Banktransfer
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('trezo/cielo/form/boleto.phtml');
    }
}
