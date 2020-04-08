<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     Leonardo Lopes de Albuquerque <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Block_Form_Dc extends Mage_Payment_Block_Form_Cc
{

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('trezo/cielo/form/dc.phtml');
    }

    public function getDcTypes()
    {
        $dcTypesAvaliable = Mage::getStoreConfig('payment/cielo_dc/dctypes');

        $dcTypes = [];
        if ($dcTypesAvaliable != '') {
            $dcTypes = explode(",", $dcTypesAvaliable);
        }

        return $dcTypes;
    }

    /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getDcMonths()
    {
        $months = $this->getData('dc_months');
        if (is_null($months)) {
            $months[0] = $this->__('Month');
            $months = array_merge($months, $this->_getConfig()->getMonths());
            $this->setData('dc_months', $months);
        }
        return $months;
    }

    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getDcYears()
    {
        $years = $this->getData('dc_years');
        if (is_null($years)) {
            $years = $this->_getConfig()->getYears();
            $years = array(0 => $this->__('Year')) + $years;
            $this->setData('dc_years', $years);
        }
        return $years;
    }

    /**
     * Retrive has verification configuration
     *
     * @return boolean
     */
    public function hasVerification()
    {
        if ($this->getMethod()) {
            $configData = $this->getMethod()->getConfigData('useccv');
            if (is_null($configData)) {
                return true;
            }
            return (bool)$configData;
        }
        return true;
    }
}