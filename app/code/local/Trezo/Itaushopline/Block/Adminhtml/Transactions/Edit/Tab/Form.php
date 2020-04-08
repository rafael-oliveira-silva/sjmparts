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

class Trezo_Itaushopline_Block_Adminhtml_Transactions_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $data = Mage::registry("itaushopline_data")->getData();

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("itaushopline_form", array("legend"=>Mage::helper("itaushopline")->__("Item Information")));

        $config = Mage::getConfig();
        $fieldset->addType('expiration', $config->getBlockClassName('itaushopline/adminhtml_system_config_form_field_expiration'));
        $fieldset->addType('price', $config->getBlockClassName('itaushopline/adminhtml_system_config_form_field_price'));


        $fieldset->addField("order_id", "label", array(
        "label" => Mage::helper("itaushopline")->__("Order ID"),
        "class" => "required-entry",
        "required" => true,
        "name" => "order_id",
        "disabled" => true,
        ));

        $order_increment_id = Mage::getModel('sales/order')->load($data ['order_id'])->getIncrementId();

        $fieldset->addField("order_increment_id", "label", array(
        "label" => Mage::helper("itaushopline")->__("Order Increment ID"),
        "class" => "required-entry",
        "required" => true,
        "disabled" => true,
        "after_element_html" => $order_increment_id,
        ));

        $fieldset->addField("number", "label", array(
        "label" => Mage::helper("itaushopline")->__("ID Do Pedido No Itaú"),
        "class" => "required-entry",
        "required" => true,
        "name" => "number",
        "disabled" => true,
        ));

        $fieldset->addField("expiration", "expiration", array(
        "label" => Mage::helper("itaushopline")->__("Expiration"),
        "class" => "required-entry",
        "required" => true,
        "name" => "expiration",
        "disabled" => true,
        ));

        $fieldset->addField("amount", "price", array(
        "label" => Mage::helper("itaushopline")->__("Amount"),
        "class" => "required-entry",
        "required" => true,
        "name" => "amount",
        "disabled" => true,
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
