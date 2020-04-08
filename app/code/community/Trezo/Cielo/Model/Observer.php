<?php

class Trezo_Cielo_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function verifyCheckout(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $paymentMethod = $order->getPayment()->getMethodInstance();

        if (!($paymentMethod instanceof Trezo_Cielo_Model_Payment_CcMethod)) {
            return;
        }
        
        if ($order->canInvoice()) {
            try {
                // $order->sendNewOrderEmail();

                $order->setState(Mage_Sales_Model_Order::STATE_NEW);
                $order->setStatus('pending');
                $order->save();
            } catch (Exception $e) {
            }
        }
    }
}
