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

class Trezo_Itaushopline_Block_Adminhtml_System_Config_Form_Field_Export_Pac extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $buttonBlock = $this->getLayout()->createBlock('adminhtml/widget_button');

        $params = array(
            'website' => $buttonBlock->getRequest()->getParam('website'),
            'store' => $buttonBlock->getRequest()->getParam('store')
        );

        $data = array(
            'label'     => Mage::helper('freight')->__('Export PAC to CSV'),
            'onclick'   => "setLocation('" . Mage::helper('adminhtml')->getUrl('freight/adminhtml_freights/export', $params) . "carrier/pac')",
            'class'     => chr(0),
        );

        $html = $buttonBlock->setData($data)->toHtml();

        return $html;
    }
}
