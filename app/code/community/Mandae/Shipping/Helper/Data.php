<?php
/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @copyright  Copyright (c) 2017 Bizcommerce
 */
class Mandae_Shipping_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $message
     * @return void
     */
    public function log($message)
    {
        Mage::log($message, null, 'mandae.log');
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return (Mage::getStoreConfig('carriers/mandae/environment') == 'sandbox') ? true : false;
    }

    /**
     * @param string $str
     * @return string
     */
    public function slugify($str)
    {
        $str = Mage::helper('core')->removeAccents($str);

        $urlKey = preg_replace('#[^0-9a-z+]+#i', '-', $str);
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');

        return $urlKey;
    }
}
