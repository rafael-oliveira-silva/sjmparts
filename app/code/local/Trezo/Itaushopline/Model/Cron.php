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

class Trezo_Itaushopline_Model_Cron
{

    public function _debug($debugData)
    {
        if (Mage::getStoreConfig('payment/itaushopline_settings/debug')) {     //ve se no modulo esta ligado o modo debug
            Mage::log($debugData, null, 'cronItaushopline.log', true);
        }
    }

    public function _getStoreConfig($field)
    {
        return Mage::getStoreConfig("payment/itaushopline_settings/$field");
    }

    public function limpageral($amount)
    {
        $amountStr = number_format($amount, 2, '', '');
        $amountStr = str_ireplace(".", "", $amountStr);
        $amountStr = str_ireplace(",", "", $amountStr);

        return $amountStr;
    }

    public function sendCancellationEmail($order)
    {
        // Get store data
        $storeData = array(
            'name' => Mage::getStoreConfig('trans_email/ident_general/name'),
            'email' => Mage::getStoreConfig('trans_email/ident_general/email')
        );

        // Load order data
        $emailTemplateVariables = array(
            'username' => $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname(),
            'order_id' => $order->getIncrementId(),
            'store_url' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
            'order_status' => $order->getStatus()
        );

        // Load template and customer data
        $templateId = ((int)Mage::getStoreConfig('payment/itaushopline_settings/cancellation_email_template'));
        $customerEmail = $order->getCustomerEmail();
        $customerName = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();

        // Send email based on template
        Mage::getModel('core/email_template')
            ->sendTransactional(
                $templateId,
                $storeData,
                $customerEmail,
                $customerName,
                $emailTemplateVariables
            );
    }

    public function notifyAdmin($tipPag, $sitPag, $pedido, $resposta)
    {
        $enableEmail = Mage::getStoreConfig('payment/itaushopline_settings/enable_email');

        if ($enableEmail) {
            $htmlBody = $tipPag.":".$sitPag." - O pedido " . $pedido . " foi atualizado pelo Itaushopline. \n \n Status de resposta: \n" .$resposta. ".";

            $mail = Mage::getModel('core/email');

            $mail->setToName(Mage::getStoreConfig('payment/itaushopline_settings/email_name'));
            $mail->setToEmail(Mage::getStoreConfig('payment/itaushopline_settings/email_to'));
            $mail->setBody(utf8_decode($htmlBody));
            $mail->setSubject('=?utf-8?B?'.base64_encode($tipPag.":".$sitPag." - Pedido " . $pedido . ": Itaushopline").'?=');
            $mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_sales/email'));
            $mail->setFromName(Mage::getStoreConfig('trans_email/ident_sales/name'));
            $mail->setType('text');

            try {
                $mail->send();
            } catch (Exception $e) {
                $this->_debug('Erro ao enviar email, pedido: '.$pedido.' | ERRO: '.$ex->getMessage());
            }
        }
    }

    public function run()
    {
        if (!Mage::getStoreConfig('payment/itaushopline_standard/active_cron')) {
            return false;
        }
        
        $orders = Mage::getModel('sales/order')->getCollection();

        // Pega os pedidos que est�o com status pendente
        $pendingStatus = explode(',', Mage::getStoreConfig('payment/itaushopline_standard/status_to_verify'));
        $orders->addAttributeToFilter('status', array('in' => $pendingStatus));

        $orders->getSelect()->joinLeft(
            array('payment_table' => Mage::getSingleton('core/resource')->getTableName('sales/order_payment')),
            "main_table.entity_id = payment_table.parent_id",
            array("method"),
            null
        );

        // Dentre os pendentes, apenas com forma de pagamento ItauShopline
        $orders->addAttributeToFilter(
            'payment_table.method',
            array(
                'in' => array(
                    Trezo_Itaushopline_Model_Standard::PAYMENT_METHOD
                )
            )
        );

        //Pega data atual
        $dataatual = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
        foreach ($orders as $order) {
            $orderIncrementId = $order->getIncrementId();
            $order->loadByIncrementId($orderIncrementId);

            // Resgatar o retorno do itau e armazenar em var's para posterior compara��o
            $code = $this->_getStoreConfig('code');
            $key = $this->_getStoreConfig('key');
            $formato = "1"; //retorno ser� dado em XML caso seja 0 o retorno � html

            $number = $order->getIncrementId();
            $pedido = substr($orderIncrementId, -8);
            $orderId = $order->getId();

            // Grava log
            $this->_debug('Verificando pedido [itau'.$pedido.'/magento:'.$number.'] (code:' . $code . ' - Key:' . $key.')');

            // Resgata dados para consulta
            $dados = Mage::getModel('itaushopline/itaucripto')->geraConsulta($code, $pedido, $formato, $key);

            // Solicita consulta
            $link = "https://shopline.itau.com.br/shopline/consulta.aspx?dc=" . $dados;
            $this->_debug("Link p/ consulta ".$link);

            $xml = simplexml_load_file($link);
            $sitPag = '-1';

            foreach ($xml->PARAMETER->PARAM as $itauXml) {
                switch ($itauXml['ID']) {
                    case "codEmp":
                        $codEmp    = $itauXml['VALUE'];
                        break;
                    case "Pedido":
                        $pedido    = $itauXml['VALUE'];
                        break;
                    case "Valor":
                        $valor     = $itauXml['VALUE'];
                        break;
                    case "tipPag":
                        $tipPag    = $itauXml['VALUE'];
                        break;
                    case "sitPag":
                        $sitPag    = $itauXml['VALUE'];
                        break;
                    case "dtPag":
                        $dtPag     = $itauXml['VALUE'];
                        break;
                    case "codAuto":
                        $codAuto   = $itauXml['VALUE'];
                        break;
                    case "numId":
                        $numId     = $itauXml['VALUE'];
                        break;
                    case "compVend":
                        $compVend  = $itauXml['VALUE'];
                        break;
                    case "tipCart":
                        $tipCart   = $itauXml['VALUE'];
                        break;
                }
            }

            /*
             ###########################################################
            TRATAMENTO DE ERROS
            ###########################################################
            */

            // Grava log
            $this->_debug('tipPag' . $tipPag);
            $this->_debug('sitPag' . $sitPag);


            $mensagem = "";
            switch ($sitPag) {
                case '01':
                    $mensagem = "01 - Pagamento nao Selecionado (tente novamente)";
                    break;
                case '02';
                    $mensagem = "02 - Erro no processamento da consulta (tente novamente)";
                    break;
                case '03':
                    $mensagem = "03 - Boleto fora do prazo de pagamento ou Pagamento nao Selecionado (tente novamente)";
                    break;
                case '04':
                    $mensagem = "04 - Boleto emitido com sucesso, aguardando pagamento";
                    break;
                case '05':
                    $mensagem = "05 - Pagamento efetuado, aguardando compensacao";
                    break;
                case '06':
                    $mensagem = "06 - Pagamento nao compensado";
                    break;
                case '08':
                    $mensagem = "08 - Varias tentativas bloqueio de 30min";
                    break;
                default:
                    $mensagem = "99 - Erro inesperado sem num de retorno";
            }

            //Trata os erros ou seja diferente de pagamento confirmado
            if ($sitPag != 00) {
                $transactions = Mage::getModel('itaushopline/transactions')->load($orderId, 'order_id');
                $trans_order_id = $transactions->getOrderId();
                $trans_expiration = $transactions->getExpiration();
                $trans_expiration = date('Y-m-d', strtotime($trans_expiration));

                // Grava Log
                $this->_debug("Novo status pedido num ".$pedido."(msg :".$mensagem.")");
                $this->_debug('dt atual: ' . $dataatual);
                $this->_debug('dt Expiracao-DB: ' . $transactions->getExpiration());
                //$this->_debug('dt $pedidoOrder-' . $pedidoOrder );
                //$this->_debug('dt $trans_order_id-' . $trans_order_id );

                //ACAO CANCELAR PEDIDO - (fora do prazo)
                // && $tipPag == 00 && $sitPag == 03 ||  && $tipPag == 02 && $sitPag == 04
                if ($dataatual > $trans_expiration) { // && ($tipPag == 00 && $sitPag == 03 || $tipPag == 02 && $sitPag == 04)) {
                    $msg = $tipPag.':'.$sitPag.': Pedido cancelado (Fora do prazo) -'.$dataatual." > ".$trans_expiration.'';
                    $this->_debug($msg);
                    $this->cancelOrder($order, $msg);
                }
            }

            /*
            ###########################################################
            PAGAMENTO CONFIRMADO (00 - Pagamento efetuado)
            ###########################################################
            */
            if (!isset($valor) || empty($valor)) {
                 $this->_debug('Valor pago não foi encontrado no XML de retorno!');
                 continue;
            }

            $valorPago =  str_ireplace(",", "", $valor);
            $valorPedido = $this->limpageral($order->getGrandTotal());

            // Grava Log
            $this->_debug("valor ".$valorPago);
            $this->_debug("getGrandTotal ".$valorPedido);

            //Faz a compara��o entre o valor resgatado no XML e o valor do pedido
            if ($valorPago == $valorPedido && $sitPag == 00) {
                $msg = $tipPag.":".$sitPag.": Pagamento confirmado no Itaushopline! - Valor total de ".$valorPago." data do pagamento:".$dtPag;

                // Grava Log
                $this->_debug("[itau".$pedido."/mage:".$number."] - ".$tipPag.":".$msg);

                $this->createInvoice($order, $msg);

                //Gerando Fatura automaticamente #####################
                //Gerando Fatura automaticamente #####################
                //Gerando Fatura automaticamente #####################

                //$invoiceId = Mage::getModel('sales/order_invoice_api')->create($order->getIncrementId(), array());
                //$invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceId);

                // envia email de confirmacao de fatura
                //$invoice->sendEmail(true);
                //$invoice->setEmailSent(true);
                //$invoice->save();
            } elseif ($sitPag == 00 && $valorPago != $valorPedido) {
                /*
				###########################################################
				VALOR DIVERGENTE
				###########################################################
				*/

                // Grava Log
                $this->_debug("[itau".$pedido."/mage:".$number."] - Atencao: Pedido com valor divergente no Itaushopline!".$tipPag.":".$sitPag." - Valor pago: ".$valorPago);

                // Adiciona entrada no historico do pedido avisando do sucesso
                $order->addStatusHistoryComment("Atencao: Pedido com valor divergente no Itaushopline!".$tipPag.":".$sitPag." - Valor pago: ".$valorPago);
                $order->save();

                // Envia email de notifica��o
                $this->notifyAdmin($tipPag, $sitPag, $pedido, "[itau".$pedido."/mage:".$number."] - Atencao: Pedido com valor divergente no Itaushopline!".$tipPag.":".$sitPag." - Valor pago: ".$valorPago);

                // Muda status
                $order->setStatus(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW);
                $order->save();
            }

            $this->_debug("--");
        }//fim de foreach
    }//fim de run()

    private function createInvoice($order, $msg='')
    {
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
            $msg,
            false
        )->save();

        return true;
    }

    private function cancelOrder($order, $comment)
    {
        if ($order->canCancel()) {
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
}
