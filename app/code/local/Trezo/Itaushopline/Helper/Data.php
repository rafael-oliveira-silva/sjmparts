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

class Trezo_Itaushopline_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getYesNo()
    {
        return array (1 => Mage::helper('utils')->__('Enabled'), 0 => Mage::helper('utils')->__('Disabled'));
    }

    public function getCurrencySymbol()
    {
        return Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
    }

    public function toOptions(Mage_Core_Model_Mysql4_Collection_Abstract $collection, array $fields)
    {
        $key = key($fields);
        $value = $fields [$key];

        $data = $collection->toArray(array ($key, $value));

        $result = '';
        foreach ($data ['items'] as $item) {
            $result [$item [$key]] = $item [$value];
        }

        return $result;
    }

    public function canViewOrder($order)
    {
        if (!$order || !$order->getId() || !$order->getPayment()) {
            return false;
        }

        $customerId = Mage::getSingleton ('customer/session')->getCustomerId();
        $method = $order->getPayment()->getMethod();
        if (
            (!is_null($order->getCustomerId()) && ($order->getCustomerId() != $customerId))
            || ($method != Trezo_Itaushopline_Model_Standard::PAYMENT_METHOD)
        ) {
            return false;
        }

        return true;
    }
}
