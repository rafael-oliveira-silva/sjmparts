<?php

/**
 * Trezo Cielo Payment Method
 *
 * @category   Trezo
 * @package    Trezo_Cielo
 * @author     André Felipe <contato@trezo.com.br>
 *
 */

use Cielo_Api_CieloEcommerce as CieloEcommerce;

class Trezo_Cielo_Model_Cielo_CancelTransaction extends Trezo_Cielo_Model_Cielo_AbstractTransaction
{
    private $paymentId;

    public function __construct(Mage_Sales_Model_Order_Payment $payment)
    {
        parent::__construct($payment);

        // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
        self::$sale = new CieloEcommerce($this->merchant, $this->environment);
        $this->setTransactionData();
    }

    private function setTransactionData()
    {
        // Define dados da requisição
        $this->paymentId = $this->payment->getAdditionalInformation('payment_id');
    }

    public function getResponseTransaction()
    {
        return self::$sale->cancelSale($this->paymentId);
    }

    public function cancelTransaction(Varien_Object $payment, $amount)
    {
        try {
            $response = $this->getResponseTransaction();

            $authorizationTransaction = $payment->getAuthorizationTransaction();

            //Salva a quantia cancelada
            $payment->setAmountCanceled($amount);

            //Registra a transação
            $transaction = Mage::getModel('sales/order_payment_transaction');
            $transaction->setOrderPaymentObject($payment);
            $transaction->setTxnId($payment->getTransactionId());
            $transaction->setTxnType(Mage_Sales_Model_Order_Payment_Transaction::TYPE_VOID);
            $transaction->setAdditionalInformation('response', serialize($response));
            $transaction->setIsClosed(true);
            $transaction->setParentId($authorizationTransaction->getId());
            $transaction->save();
        } catch (ApiError $e) {
            Mage::throwException(Mage::helper('trezo_cielo')->__("Error returned from Cielo: ") . $e->getCieloError()->getMessage());
        }
    }
}