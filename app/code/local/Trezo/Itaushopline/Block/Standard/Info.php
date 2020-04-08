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

class Trezo_Itaushopline_Block_Standard_Info extends Mage_Payment_Block_Info
{
    private $order = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('itaushopline/standard/info.phtml');
    }

    /**
     * Render block HTML only on view action cause when create shipment it not works to create tracking with this block rendered with a form
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->getRequest()->getActionName() == 'new') {
            return '';
        }

        return parent::_toHtml();
    }

    public function getOrder()
    {
        if (!$this->order) {
            if ((!$order = Mage::registry('current_order')) && ($info = $this->getInfo())) {
                $order = $info->getOrder();
            }

            $this->order = $order;
        }

        return $this->order;
    }

    public function getBoletoUrl()
    {
        return $this->getUrl('itaushopline/standard/show/pedido/'.$this->getOrder()->getIncrementId());
    }

    protected function _getStoreConfig($field)
    {
        return Mage::getStoreConfig("payment/itaushopline_settings/{$field}");
    }

    public function getSubmitTransactionInformation()
    {
        $order = $this->getOrder();
        if (empty($order)) {
            return;
        }

        $order_id = $order->getId();

        $collection = Mage::getModel('itaushopline/transactions')->getCollection();
        $collection->getSelect()->where("order_id = {$order_id}");

        $data = $collection->getFirstItem()->toArray();

        $submit_form = new Varien_Data_Form();
        $fieldset = $submit_form->addFieldset('fieldset', array ('legend' => null));
        $fieldset->addField('submit_dc', 'hidden', array(
            'name' => 'DC',
            'value' => $data ['submit_dc'],
        ));

        return $submit_form->toHtml();
    }

    public function getQueryTransactionInformation()
    {
        $order = $this->getOrder();
        if (empty($order)) {
            return;
        }

        $order_id = $order->getId();

        $collection = Mage::getModel('itaushopline/transactions')->getCollection();
        $collection->getSelect()->where("order_id = {$order_id}");

        $data = $collection->getFirstItem()->toArray();

        $query_form = new Varien_Data_Form();
        $fieldset = $query_form->addFieldset('fieldset', array ('legend' => null));
        $fieldset->addField('query_dc', 'hidden', array(
            'name' => 'DC',
            'value' => $data ['query_dc'],
        ));
        $fieldset->addField('submit', 'submit', array(
            'label' => Mage::helper('itaushopline')->__('Query this transaction'),
            'value' => Mage::helper('itaushopline')->__('Query'),
        ));

        return $query_form->toHtml();
    }

    public function canPrintBillet()
    {
        if (!$this->getOrder()) {
            return false;
        }

        $transactionsModel = Mage::getModel('itaushopline/transactions');
        $transactionsModel->setOrder($this->getOrder());

        if ($transactionsModel->orderIsCanceled()) {
            return false;
        }

        if ($transactionsModel->billetIsExpired()) {
            return false;
        }

        return true;
    }
}
