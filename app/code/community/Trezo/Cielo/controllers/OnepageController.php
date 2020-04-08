<?php
/**
 * Created by PhpStorm.
 * User: leonardo
 * Date: 29/11/17
 * Time: 09:54
 */

require_once Mage::getModuleDir('controllers', "Mage_Checkout") . DS . "OnepageController.php";

class Trezo_Cielo_OnepageController extends Mage_Checkout_OnepageController
{


    public function validateDebitCardOrderAction()
    {

        $session = $this->getOnepage()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastOrderId = $session->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($lastOrderId);

        if ($this->isDebitCardPayment($order)) {

            $apiOrderData = Mage::getModel('trezo_cielo/cielo_queryTransaction', $order->getPayment())->getResponseTransaction();
            $status = $apiOrderData->getPayment()->getStatus();

            $order->getPayment()->setStatus($status);

            if (!Mage::helper('trezo_cielo')->validateStatus($status)) {
                $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
                $order->save();
                $this->_redirect('checkout/onepage/failure');
                return;
            } else {
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
                $order->save();
            }
        }

        $this->_redirect('checkout/onepage/success');
    }

    private function isDebitCardPayment($order)
    {
        $paymentAdditionalInformation = $order->getPayment()->getAdditionalInformation();
        return isset($paymentAdditionalInformation['payment_type']) && $paymentAdditionalInformation['payment_type'] == "cielo_debit_card";
    }
}