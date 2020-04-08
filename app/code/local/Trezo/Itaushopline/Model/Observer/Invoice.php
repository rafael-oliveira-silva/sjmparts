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

class Trezo_Itaushopline_Model_Observer_Invoice
{
    public function capture($observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();
        if ($order->getPayment()->getMethod() == 'itaushopline_standard') {
            $ccType = $order->getPayment()->getCcType();
            $order->getPayment()->getMethod();

            if (!strcmp($ccType, 'ITAU_SLIP') || !strcmp($ccType, 'ITAU_TEF')) {
                $invoice = $event->getInvoice();

                if ($invoice->canCapture()) {
                    $invoice->capture()->save();
                }
            }
        }

        return $this;
    }
}
