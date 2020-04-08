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

class Trezo_Itaushopline_Block_Adminhtml_Transactions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId("itaushopline_transactions_grid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("itaushopline/transactions")->getCollection();
        $collection->getSelect()->join(
            array('table_sales_order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
            'main_table.order_id = table_sales_order.entity_id',
            array('increment_id')
        );
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("id", array(
        "header" => Mage::helper("itaushopline")->__("ID"),
        "align" =>"right",
        "width" => "50px",
        "index" => "id",
        ));
        $this->addColumn("order_increment_id", array(
        "header" => Mage::helper("itaushopline")->__("ID Do Pedido"),
        "align" =>"left",
        "index" => "increment_id",
        ));
        $this->addColumn("number", array(
        "header" => Mage::helper("itaushopline")->__("ID Do Pedido No Itaú"),
        "align" =>"left",
        "index" => "number",
        ));
        $this->addColumn("expiration", array(
        "header" => Mage::helper("itaushopline")->__("Expiration"),
        "align" => "right",
        "index" => "expiration",
        "type" => "date",
        "renderer" => "itaushopline/adminhtml_widget_grid_column_renderer_expiration",
        ));
        $this->addColumn("amount", array(
        "header" => Mage::helper("itaushopline")->__("Amount"),
        "align" =>"right",
        "index" => "amount",
        "type" => "currency",
        "currency" => "base_currency_code",
        "renderer" => "itaushopline/adminhtml_widget_grid_column_renderer_price"
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/view", array("id" => $row->getId()));
    }
}
