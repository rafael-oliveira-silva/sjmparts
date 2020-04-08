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

class Trezo_Itaushopline_Block_Adminhtml_System_Config_Form_Field_Expiration extends Varien_Data_Form_Element_Text
{
    public function getElementHtml()
    {
        $today = strtotime(date('Y-m-d'));
        $value = strtotime($this->getEscapedValue());

        $color = $value > $today ? 'green' : 'red';
        $content = strftime('%c', $value);

        return "<span style='color:{$color};font-weight:bold;'>{$content}</span>";
    }
}
