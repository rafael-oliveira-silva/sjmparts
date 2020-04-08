<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

use Cielo_Merchant as Merchant;
use Cielo_Api_Environment as Environment;

abstract class Trezo_Cielo_Model_AbstractCielo extends Mage_Core_Model_Abstract
{
    protected static $sale;
    protected $environment;
    protected $merchant;

    public function __construct()
    {
        if ($this->isSandbox()) {
            $this->environment = Environment::sandbox();
        } else {
            $this->environment = Environment::production();
        }

        // get admin configs
        $merchantKey = $this->getConfig('merchant_key');
        $merchantId = $this->getConfig('merchant_id');

        $this->merchant = new Merchant($merchantId, $merchantKey);
    }

    public function getConfig($key, $group = 'cielo_general')
    {
        return Mage::helper('trezo_cielo')->getConfig($key, $group);
    }

    public function isSandbox()
    {
        return Mage::helper('trezo_cielo')->isSandbox();
    }
}