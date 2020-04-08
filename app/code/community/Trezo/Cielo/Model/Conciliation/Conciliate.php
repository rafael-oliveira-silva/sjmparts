<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

class Trezo_Cielo_Model_Cielo_Conciliation_Conciliate extends Trezo_Cielo_Model_AbstractCielo
{
    private $cieloOrder;
    /** @var  Mage_Sales_Model_Order_Payment */
    private $payment;
    /** @var  Mage_Sales_Model_Order */
    private $order;

    public function __construct(\Gateway\One\DataContract\Response\BaseResponse $cieloOrder, $order)
    {
        parent::__construct();

        $this->cieloOrder = $cieloOrder;
        $this->order = $order;
        $this->payment = $order->getPayment();
    }

    public function processNotificationConciliation()
    {
        $status = $this->cieloOrder->getData()->OrderStatus;
        switch ($status) {
            case \Gateway\One\DataContract\Enum\OrderStatusEnum::Paid:
                $this->createInvoice();
                break;
            case \Gateway\One\DataContract\Enum\OrderStatusEnum::Closed:
            case \Gateway\One\DataContract\Enum\OrderStatusEnum::Canceled:
                $this->cancelOrder('Cancelado Postback: ' . $status);
                break;
            default:
                break;
        }

    }

    public function processQueryConciliation()
    {
        if ($creditCards = $this->cieloOrder->getData()->CreditCardTransactionDataCollection) {
            $this->proccessTransactionStatus($creditCards[0]->CreditCardTransactionStatus);
        } else if ($boletos = $this->cieloOrder->getData()->BoletoTransactionDataCollection) {
            $this->proccessTransactionStatus($boletos[0]->BoletoTransactionStatus);
        }
    }

    public function proccessTransactionStatus($status)
    {
        switch ($status) {
            case 'Captured':
                $this->createInvoice();
                break;
            case 'AuthorizedPendingCapture':
                break;
            default:
                $this->cancelOrder('Cancelado: ' . $status);
                break;
        }
    }

    private function cancelOrder($comment)
    {
        $order = $this->getOrder();
        if ($order->canCancel()) {
            $comment = Mage::helper('trezo_cielo')->__($comment);
            $order->cancel();
            $order->setState(
                Mage_Sales_Model_Order::STATE_CANCELED,
                Mage_Sales_Model_Order::STATE_CANCELED,
                $comment,
                true
            );

            $order->save();
        }
    }

    private function createInvoice()
    {
        $order = $this->getOrder();
        if (!$order ->canInvoice()) {
            return false;
        }

        $invoice = $order->prepareInvoice();
        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
        $invoice->register()->pay();
        $invoice->sendEmail();

        Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder())
            ->save();

        $order ->setState(
            Mage_Sales_Model_Order::STATE_PROCESSING,
            Mage_Sales_Model_Order::STATE_PROCESSING,
            Mage::helper('trezo_cielo')->__('Invoice created by Cron.'),
            true
        )->save();
    }

    private function getPayment()
    {
        if (!$this->payment) {
            $this->payment = $this->getOrder()->getPayment();
        }

        return $this->payment;
    }

    private function getOrder()
    {
        return $this->order;
    }
}
