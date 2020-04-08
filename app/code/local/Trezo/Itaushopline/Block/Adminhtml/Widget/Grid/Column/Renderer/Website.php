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


class Trezo_Itaushopline_Block_Adminhtml_Widget_Grid_Column_Renderer_Website extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $website_id = $row->getData($this->getColumn()->getIndex());

        $website = Mage::getModel('itaushopline/config')->getWebsite('website_id', $website_id);

        return !empty($website) ? $website->getName() : $this->__('All');
    }
}
