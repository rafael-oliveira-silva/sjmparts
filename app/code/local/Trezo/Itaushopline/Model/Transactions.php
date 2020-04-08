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

class Trezo_Itaushopline_Model_Transactions extends Mage_Core_Model_Abstract
{
    private $order;

    protected function _construct()
    {
        $this->_init("itaushopline/transactions");
    }

    public function orderIsCanceled()
    {
        return $this->order->getStatus() == 'canceladosac' || $this->order->isCanceled();
    }

    public function billetIsExpired()
    {
        $transaction = $this->load($this->order->getId(), 'order_id');

        $config = Mage::getStoreConfig('payment/itaushopline_settings');
        $expirationDate = new DateTime($transaction->getExpiration());
        $dateNow = new DateTime('now');

        return $dateNow->diff($expirationDate)->format("%r%a") < ($config['expiration'] * (-1));
    }

    public function setOrder(Mage_Sales_Model_Order $order)
    {
        $this->order = $order;

        return $this;
    }
}
