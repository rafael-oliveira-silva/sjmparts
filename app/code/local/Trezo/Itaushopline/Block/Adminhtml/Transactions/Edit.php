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

class Trezo_Itaushopline_Block_Adminhtml_Transactions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = "id";
        $this->_blockGroup = "itaushopline";
        $this->_controller = "adminhtml_transactions";
        $this->_removeButton('save');
        $this->_removeButton('delete');
        $this->_removeButton('reset');

        $this->_addButton("submit", array(
            "label"     => Mage::helper("itaushopline")->__("Gerar Boleto"),
            "onclick"   => "popWin ('" . $this->getSubmitUrl() . "', '','width=700,height=500,resizable=no,scrollbars=no')",
            "class"     => "go",
        ));

        $this->_addButton("query", array(
            "label"     => Mage::helper("itaushopline")->__("Query"),
            "onclick"   => "popWin ('" . $this->getQueryUrl() . "', '','width=700,height=500,resizable=no,scrollbars=no')",
            "class"     => "go",
        ));
    }

    public function getHeaderText()
    {
        return Mage::helper("itaushopline")->__("View Item '%s'", $this->htmlEscape(Mage::registry("itaushopline_data")->getId()));
    }

    private function _getTransaction()
    {
        $id = $this->getRequest()->getParam('id');

        $collection = Mage::getModel('itaushopline/transactions')->getCollection();
        $collection->getSelect()->where("id = {$id}");

        return $collection->getFirstItem()->toArray();
    }

    public function getSubmitDC()
    {
        $data = $this->_getTransaction();

        return $data ['submit_dc'];
    }

    public function getQueryDC()
    {
        $data = $this->_getTransaction();

        return $data ['query_dc'];
    }

    public function getSubmitUrl()
    {
        $submit_url = Mage::getStoreConfig('payment/itaushopline_settings/submit_url');
        $submit_dc = $this->getSubmitDC();

        return "{$submit_url}?DC={$submit_dc}";
    }

    public function getQueryUrl()
    {
        $query_url = Mage::getStoreConfig('payment/itaushopline_settings/query_url');
        $query_dc = $this->getQueryDC();

        return "{$query_url}?DC={$query_dc}";
    }
}
