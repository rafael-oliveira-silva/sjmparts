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

class Trezo_Itaushopline_Block_Adminhtml_Transactions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();

        $this->setId("itaushopline_transactions_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("itaushopline")->__("Item Information"));
    }

    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
        "label" => Mage::helper("itaushopline")->__("Item Information"),
        "title" => Mage::helper("itaushopline")->__("Item Information"),
        "content" => $this->getLayout()->createBlock("itaushopline/adminhtml_transactions_edit_tab_form")->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
