<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

require_once Mage::getModuleDir('Model', 'Trezo_Cielo') . '/Model/Conciliation/Conciliate.php';

class Trezo_Cielo_Model_Cron_Cron
{
    public function processPayments()
    {
        /* @TODO: Implementar consulta e atualização de status automática */
        die;
        $orders = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToSelect('entity_id', 'payment_method')
            ->join(
                array('payment' => 'sales/order_payment'),
                'main_table.entity_id=payment.parent_id',
                array('payment_method' => 'payment.method')
            );

        $orders->addAttributeToFilter('status', array(
            'in' => array(
                Mage_Sales_Model_Order::STATE_PENDING
                )
            ))
        ->addFieldToFilter('payment.method', array('in' => array(
                Trezo_Cielo_Model_Payment_BoletoMethod::PAYMENT_METHOD,
                Trezo_Cielo_Model_Payment_CcMethod::PAYMENT_METHOD
            )
        ))
        ->getSelect()->limit(100);

        // call walk iterator
        Mage::getSingleton('core/resource_iterator')->walk($orders->getSelect(), array(array($this, 'processPaymentsCallback')));
    }

    public function processPaymentsCallback($args)
    {
        try {
            $order = Mage::getModel('sales/order')->load($args['row']['entity_id']);
            $queryOrder = Mage::getModel('trezo_cielo/cielo_queryTransaction', $order->getPayment())->getResponseTransaction();
            (new Trezo_Cielo_Model_Cielo_Conciliation_Conciliate($queryOrder, $order))->processQueryConciliation();
        } catch (\Gateway\One\DataContract\Report\ApiError $e) {
            Mage::getStoreConfig('dev/log/exception_file');
            Mage::log($e->errorCollection, null, $file);
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
}
