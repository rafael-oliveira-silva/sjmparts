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

class Trezo_Itaushopline_Block_Adminhtml_Widget_Grid_Column_Renderer_Expiration extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Date
{
    public function render(Varien_Object $row)
    {
        $content = parent::render($row);

        $today = strtotime(date('Y-m-d'));
        $value = strtotime($row->getData($this->getColumn()->getIndex()));

        $color = $value > $today ? 'green' : 'red';

        return "<span style='color:{$color};font-weight:bold;'>$content</span>";
    }
}
