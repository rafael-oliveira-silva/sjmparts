<?php
/**
* Trezo
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.trezo.com.br for more information.
*
* @category Trezo
* @package Trezo_Itaushopline
*
* @copyright Copyright (c) 2017 Trezo. (http://www.trezo.com.br)
*
* @author Trezo Core Team <contato@trezo.com.br>
*/

class Trezo_Itaushopline_Block_Adminhtml_System_Config_Form_Field_Price extends Varien_Data_Form_Element_Text
{
    public function getElementHtml()
    {
        $value = $this->getEscapedValue();

        $currency_symbol = Mage::helper('itaushopline')->getCurrencySymbol();

        $result = $currency_symbol . '&nbsp;' . number_format($value, 2, ',', '.');

        return "<b>{$result}</b>";
    }
}
