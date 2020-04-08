<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Block_Info_Cc extends Mage_Payment_Block_Info_Cc
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('trezo/cielo/info/cc.phtml');
    }

    public function getExpDateFormatted()
    {
        $year = $this->getInfo()->getCcExpYear();
        $month = $this->getInfo()->getCcExpMonth();
        if ($year && $month) {
            return $this->_formatCardDate($year, $month);
        }

        return '';
    }
}