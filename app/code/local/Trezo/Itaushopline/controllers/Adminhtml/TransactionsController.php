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

class Trezo_Itaushopline_Adminhtml_TransactionsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("itaushopline/transactions")->_addBreadcrumb(Mage::helper("adminhtml")->__("Itau ShopLine Transactions Manager"), Mage::helper("adminhtml")->__("Itau ShopLine Transactions Manager"));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function viewAction()
    {
        $brandsId = $this->getRequest()->getParam("id");
        $brandsModel = Mage::getModel("itaushopline/transactions")->load($brandsId);
        if ($brandsModel->getId() || $brandsId == 0) {
            Mage::register("itaushopline_data", $brandsModel);
            $this->loadLayout();
            $this->_setActiveMenu("itaushopline/transactions");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Itau ShopLine Transactions Manager"), Mage::helper("adminhtml")->__("Itau ShopLine Transactions Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Itau ShopLine Transactions Description"), Mage::helper("adminhtml")->__("Itau ShopLine Transactions Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("itaushopline/adminhtml_transactions_edit"))->_addLeft($this->getLayout()->createBlock("itaushopline/adminhtml_transactions_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("itaushopline")->__("Item does not exist."));

            $this->_redirect("*/*/");
        }
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/trezo/itaushopline_transactions');
    }
}
