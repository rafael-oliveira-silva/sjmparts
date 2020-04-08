<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    PagSeguro
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * PagSeguro Standard Checkout Controller
 *
 * @author      Michael Granados <mike@visie.com.br>
 */
class PagSeguro_StandardController
    extends Mage_Core_Controller_Front_Action
{
    /**
     * Order instance
     */
    protected $_order;

    /**
     *  Get order
     *
     *  @param    none
     *  @return	  Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if ($this->_order == null) {
        }
        return $this->_order;
    }

    /**
     * Get singleton with pagseguro strandard order transaction information
     *
     * @return PagSeguro_Model_Standard
     */
    public function getStandard()
    {
        return Mage::getSingleton('pagseguro/standard');
    }

    /**
     * When a customer chooses Paypal on Checkout/Payment page
     *
     */
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setPaypalStandardQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($this->getLayout()->createBlock('pagseguro/standard_redirect')->toHtml());
        $session->unsQuoteId();
    }

    /**
     * Retorno dos dados feito pelo PagSeguro
     */
    public function obrigadoAction()
    {
        $standard = $this->getStandard();
        # ? um $_GET, trate normalmente
        if (!$this->getRequest()->isPost()) {
			$session = Mage::getSingleton('checkout/session');
            $session->setQuoteId($session->getPaypalStandardQuoteId(true));
            /**
             * set the quote as inactive after back from pagseguro
             */
            Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
            /**
             * send confirmation email to customer
             */
            $order = Mage::getModel('sales/order');
            $order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
            if($order->getId()){
                $order->sendNewOrderEmail();
            }

            $url = $standard->getConfigData('retorno');
						
            $this->_redirect('checkout/onepage/success');
        } else {
            // Vamos ao retorno autom?tico
            if (!defined('RETORNOPAGSEGURO_NOT_AUTORUN')) {
                define('RETORNOPAGSEGURO_NOT_AUTORUN', true);
                define('PAGSEGURO_AMBIENTE_DE_TESTE', true);
            }
            // Incluindo a biblioteca escrita pela Visie
            include_once(dirname(__FILE__).'/retorno.php');
            // Brincanco com a biblioteca
            RetornoPagSeguro::verifica($_POST, false, array($this, 'retornoPagSeguro'));
        }
    }
	
	/**
     * Retorno dos dados feito pelo PagSeguro
     */
    public function obrigadotesteAction()
    {
        $standard = $this->getStandard();
        # ? um $_GET, trate normalmente
        if (!$this->getRequest()->isPost()) {
			/*$session = Mage::getSingleton('checkout/session');
            $session->setQuoteId($session->getPaypalStandardQuoteId(true));*/
            /**
             * set the quote as inactive after back from pagseguro
             */
            //Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
            /**
             * send confirmation email to customer
             */
            /*$order = Mage::getModel('sales/order');
            $order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
            if($order->getId()){
                $order->sendNewOrderEmail();
            }

            $url = $standard->getConfigData('retorno');*/
			
			// Limpa a vari?vel
			unset( $order );
			// Verifica o novo status da transa??o consultando direto no PagSeguro
			$post = $this->getRequest();
			$code = $post->tk;
			
			// Envia o cURL
			// In?cio
			$cURL = curl_init(); // Inicia o cURL
			
			// Configura??es
			curl_setopt($cURL, CURLOPT_URL, 'https://ws.pagseguro.uol.com.br/v2/transactions/'.$code.'?email='.$this->getEmailPS().'&token='.$this->getTokenPS()); // Define a URL
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true); // Grava o retorno
			curl_setopt($cURL, CURLOPT_HEADER, false); // Sem cabe?alho
			curl_setopt($cURL, CURLOPT_TIMEOUT, 30); // Timeout
			curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false); // N?o verifica o SSL
			
			// Execu??o
			$resposta = curl_exec( $cURL ); // Executa o cURL
			curl_close( $cURL ); // Encerra a conex?o
			$xml = new SimpleXMLElement($resposta); // Cria um objeto com o conte?do do XML recebido
			
			// Refer?ncias
			$orderId = $xml->reference;
			$status = $xml->status; // 1 - Aguardando pagamento / 2 - Em an?lise / 3 - Paga / 4 - Dispon?vel / 5 - Em disputa / 6 - Devolvida / 7 - Cancelada
			$transactionId = $code;
			$tipoPagamento = $xml->type;
			
			$salesOrder = Mage::getSingleton('sales/order');
			$order = $salesOrder->loadByIncrementId($orderId);
			
			if( $order->getId() ){
			// Caso tenha sido pago com sucesso
			if( $status == 3 ){
				// Se n?o for poss?vel enviar a fatura...
				if( !$order->canInvoice() ){
					$order->addStatusHistory(
						$order->getStatus(), // Mant?m o status
						'Error in creating an invoice',
						$notified = FALSE
					);
				}else{
					$order->getPayment()->setTransactionId($transactionId);
					$invoice = $order->prepareInvoice();
					$invoice->register()->pay();
					$changeTo = Mage_Sales_Model_Order::STATE_BILLED;
					Mage::getModel('core/resource_transaction')
						->addObject($invoice)
						->addObject($invoice->getOrder())
						->save();
					$comment = sprintf('Invoice #%s created. Pago com %s.', $invoice->getIncrementId(), "PagSeguro");
					$order->addStatusToHistory(
						$changeTo,
						$comment,
						$notified = TRUE
					);
				}
			}else{
				// Se tiver sido cancelado
				if( $status == 7 ){ $order->cancel(); }
				else{
					// Define o nome do status
					switch($status){
						case 1:
							$statusName = 'Aguardando Pagamento';
							break;
						
						case 2:
							$statusName = 'Em an&aacute;lise';
							break;
						
						case 3:
							$statusName = 'Paga';
							break;
						
						case 4:
							$statusName = 'Dispon&iacute;vel';
							break;
						
						case 5:
							$statusName = 'Em Disputa';
							break;
						
						case 6:
							$statusname = 'Devolvida';
							break;
						
						case 7:
							$statusName = 'Cancelada';
							break;
						
						default:
							$statusName = 'Indefinido';
							break;
					}
					$changeTo = $order->getStatus();
					$comment = $statusName.' - Tipo Pagamento: '.$tipoPagamento;
					$order->addStatusToHistory(
						$changeTo,
						$comment,
						$notified = FALSE
					);
				}
			}
			$order->save();
			// Envia o e-mail ao receber a confirma??o do PagSeguro
			if( $status == 3 ){ $invoice->sendEmail(); }
			elseif( $status == 7 ){
				$storeId = Mage::app()->getStore()->getStoreId();
				$mailTemplate = Mage::getModel('core/email_template');
				$mailTemplate->setDesignConfig(
					array(
						'area' => 'frontend',
						'store' => $storeId
					)
				);
				$emailId = 3;
				$mailTemplate->sendTransactional(
					$emailId,
					array( 'email' => $this->getEmailVendas(), 'name' => $this->getEmailVendasNome() ),
					$order->getCustomerEmail(),
					$order->getCustomerName(),
					array( 'order' => $order )
				);
				
				// Envia o e-mail para todos cadastrados no admin que devem receber o e-mail de c?pia
				$copyTo = explode( ',', Mage::getStoreConfig('sales_email/invoice/copy_to') );
				foreach( $copyTo as $copy ){
					$mailTemplate->sendTransactional(
						$emailId,
						array( 'email' => $this->getEmailVendas(), 'name' => $this->getEmailVendasNome() ),
						trim($copy),
						trim($copy),
						array( 'order' => $order, 'payment_html' => $order->getPayment()->getMethodInstance()->getTitle() )
					);
				}
			}
		}
			
			return;
						
            //$this->_redirect('checkout/onepage/success');
        } else {
            // Vamos ao retorno autom?tico
            if (!defined('RETORNOPAGSEGURO_NOT_AUTORUN')) {
                define('RETORNOPAGSEGURO_NOT_AUTORUN', true);
                define('PAGSEGURO_AMBIENTE_DE_TESTE', true);
            }
            // Incluindo a biblioteca escrita pela Visie
            include_once(dirname(__FILE__).'/retorno.php');
            // Brincanco com a biblioteca
            RetornoPagSeguro::verifica($_POST, false, array($this, 'retornoPagSeguro'));
        }
    }
    
    public function retornoPagSeguro($referencia, $status, $valorFinal, $produtos, $post)
    {
        $salesOrder = Mage::getSingleton('sales/order');
        $order = $salesOrder->loadByIncrementId($referencia);

        if ($order->getId()) {
            // Verificando o Status passado pelo PagSeguro
            if (in_array(strtolower($status), array('completo', 'aprovado'))) {
                if (!$order->canInvoice()) {
                    //when order cannot create invoice, need to have some logic to take care
                    $order->addStatusToHistory(
                        $order->getStatus(), // keep order status/state
                        'Error in creating an invoice',
                        $notified = false
                    );
                } else {
                    $order->getPayment()->setTransactionId($post->TransacaoID);
                    $invoice = $order->prepareInvoice();
                    $invoice->register()->pay();
                    $changeTo = Mage_Sales_Model_Order::STATE_BILLED;                    
                    Mage::getModel('core/resource_transaction')
                       ->addObject($invoice)
                       ->addObject($invoice->getOrder())
                       ->save();
                    $comment = sprintf('Invoice #%s created. Pago com %s.', $invoice->getIncrementId(), "PagSeguro");
                    $order->addStatusToHistory(
                       $changeTo,
                       $comment,
                       $notified = true
                    );
                }
            } else {
                // N?o est? completa, vamos processar...
                $comment = $status;
                if ( strtolower(trim($status))=='cancelado' ) {
                    $order->cancel();
                } else {
                    // Esquecer o Cancelado e o Aprovado/Conclu?do
                    $changeTo = Mage_Sales_Model_Order::STATE_HOLDED;
                    $comment .= ' - ' . $post->TipoPagamento;
					$order->addStatusToHistory(
						$changeTo,
						$comment,
						$notified = false
					);
                }
            }
            $order->save();
            // Enviar o e-mail assim que receber a confirma??o
            if (in_array(strtolower($status), array('completo', 'aprovado'))) {
                $order->sendNewOrderEmail();
            }
        }
        
    }
	
	public function notificacaoAction(){
		$post = $this->getRequest();
		$code = $post->notificationCode;
		
		// Envia cURL
		// In?cio
		$cURL = curl_init();
		
		// Configura??es
		curl_setopt($cURL, CURLOPT_URL, 'https://ws.pagseguro.uol.com.br/v2/transactions/notifications/'.$code.'?email='.$this->getEmailPS().'&token='.$this->getTokenPS()); // Define a URL
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true); // Grava o retorno
		curl_setopt($cURL, CURLOPT_HEADER, false); // Sem cabe?alho
		curl_setopt($cURL, CURLOPT_TIMEOUT, 30); // Timeout
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false); // N?o verifica o SSL
		
		// Executa
		$resposta = curl_exec($cURL); // Executa o cURL
		curl_close($cURL); // Encerra a conex?o
		$xml = new SimpleXMLElement($resposta); // Cria um objeto com o conte?do do XML recebido
		
		// Refer?ncias
		$orderId = $xml->reference;
		$status = $xml->status; // 1 - Aguardando pagamento / 2 - Em an?lise / 3 - Paga / 4 - Dispon?vel / 5 - Em disputa / 6 - Devolvida / 7 - Cancelada
		$transactionId = $xml->code;
		$tipoPagamento = $xml->type;
		
		$salesOrder = Mage::getSingleton('sales/order');
		$order = $salesOrder->loadByIncrementId($orderId);
		
		if( $order->getId() ){
			// Caso tenha sido pago com sucesso
			if( $status == 3 ){
				// Se n?o for poss?vel enviar a fatura...
				if( !$order->canInvoice() ){
					$order->addStatusHistory(
						$order->getStatus(), // Mant?m o status
						'Error in creating an invoice',
						$notified = FALSE
					);
				}else{
					$order->getPayment()->setTransactionId($transactionId);
					$invoice = $order->prepareInvoice();
					$invoice->register()->pay();
					$changeTo = Mage_Sales_Model_Order::STATE_BILLED;
					Mage::getModel('core/resource_transaction')
						->addObject($invoice)
						->addObject($invoice->getOrder())
						->save();
					$comment = sprintf('Invoice #%s created. Pago com %s.', $invoice->getIncrementId(), "PagSeguro");
					$order->addStatusToHistory(
						$changeTo,
						$comment,
						$notified = TRUE
					);
				}
			}else{
				// Se tiver sido cancelado
				/*if( $status == 7 ){
					if( $order->getStatus() != 'canceled' ) $order->cancel()->save();
				}*/
				if($status != 7){
					// Define o nome do status
					switch($status){
						case 1:
							$statusName = 'Aguardando Pagamento';
							break;
						
						case 2:
							$statusName = 'Em an&aacute;lise';
							break;
						
						case 3:
							$statusName = 'Paga';
							break;
						
						case 4:
							$statusName = 'Dispon&iacute;vel';
							break;
						
						case 5:
							$statusName = 'Em Disputa';
							break;
						
						case 6:
							$statusname = 'Devolvida';
							break;
						
						case 7:
							$statusName = 'Cancelada';
							break;
						
						default:
							$statusName = 'Indefinido';
							break;
					}
					$changeTo = $order->getStatus();
					$comment = $statusName.' - Tipo Pagamento: '.$tipoPagamento;
					$order->addStatusToHistory(
						$changeTo,
						$comment,
						$notified = FALSE
					);
				}
				
				
				/*if( $status == 7 ){
					if( $order->getStatus() != 'canceled' ) $order->cancel()->save();
				}*/
			}
			$order->save();
			// Envia o e-mail ao receber a confirma??o do PagSeguro
			if( $status == 3 ){ $invoice->sendEmail(); }
			elseif( $status == 7 && $order->getStatus() != 'canceled' ){
				$order->cancel()->save();
				$storeId = Mage::app()->getStore()->getStoreId();
				$mailTemplate = Mage::getModel('core/email_template');
				$mailTemplate->setDesignConfig(
					array(
						'area' => 'frontend',
						'store' => $storeId
					)
				);
				$emailId = 3;
				// Envia o e-mail para o cliente
				$mailTemplate->sendTransactional(
					$emailId,
					array( 'email' => $this->getEmailVendas(), 'name' => $this->getEmailVendasNome() ),
					$order->getCustomerEmail(),
					$order->getCustomerName(),
					array( 'order' => $order, 'payment_html' => $order->getPayment()->getMethodInstance()->getTitle() )
				);
				
				// Envia o e-mail para todos cadastrados no admin que devem receber o e-mail de c?pia
				$copyTo = explode( ',', Mage::getStoreConfig('sales_email/invoice/copy_to') );
				foreach( $copyTo as $copy ){
					$mailTemplate->sendTransactional(
						$emailId,
						array( 'email' => $this->getEmailVendas(), 'name' => $this->getEmailVendasNome() ),
						trim($copy),
						trim($copy),
						array( 'order' => $order, 'payment_html' => $order->getPayment()->getMethodInstance()->getTitle() )
					);
				}
			}
		}
	}
	
	public function notificacaoTesteTHMPAction(){
		ini_set('display_errors', true);
		$post = $this->getRequest();
		$code = $post->notificationCode;
		
		// Envia cURL
		// In?cio
		/*$cURL = curl_init();
		
		// Configura??es
		curl_setopt($cURL, CURLOPT_URL, 'https://ws.pagseguro.uol.com.br/v2/transactions/notifications/'.$code.'?email='.$this->getEmailPS().'&token='.$this->getTokenPS()); // Define a URL
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true); // Grava o retorno
		curl_setopt($cURL, CURLOPT_HEADER, false); // Sem cabe?alho
		curl_setopt($cURL, CURLOPT_TIMEOUT, 30); // Timeout
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false); // N?o verifica o SSL
		
		// Executa
		$resposta = curl_exec($cURL); // Executa o cURL
		curl_close($cURL); // Encerra a conex?o
		$xml = new SimpleXMLElement($resposta); // Cria um objeto com o conte?do do XML recebido
		
		// Refer?ncias
		$orderId = $xml->reference;
		$status = $xml->status; // 1 - Aguardando pagamento / 2 - Em an?lise / 3 - Paga / 4 - Dispon?vel / 5 - Em disputa / 6 - Devolvida / 7 - Cancelada
		$transactionId = $xml->code;
		$tipoPagamento = $xml->type;*/
		
		$salesOrder = Mage::getSingleton('sales/order');
		$order = $salesOrder->loadByIncrementId(100000617);
		
		var_dump( $order->getStatus() );
		
		if( $order->getId() ){
			// Caso tenha sido pago com sucesso
			if( $status == 3 ){
				// Se n?o for poss?vel enviar a fatura...
				if( !$order->canInvoice() ){
					$order->addStatusHistory(
						$order->getStatus(), // Mant?m o status
						'Error in creating an invoice',
						$notified = FALSE
					);
				}else{
					$order->getPayment()->setTransactionId($transactionId);
					$invoice = $order->prepareInvoice();
					$invoice->register()->pay();
					$changeTo = Mage_Sales_Model_Order::STATE_BILLED;
					Mage::getModel('core/resource_transaction')
						->addObject($invoice)
						->addObject($invoice->getOrder())
						->save();
					$comment = sprintf('Invoice #%s created. Pago com %s.', $invoice->getIncrementId(), "PagSeguro");
					$order->addStatusToHistory(
						$changeTo,
						$comment,
						$notified = TRUE
					);
				}
			}else{
				// Se tiver sido cancelado
				if( $status == 7 ){
					if( $order->getStatus() != 'canceled' ) $order->cancel()->save();
				}
				else{
					// Define o nome do status
					switch($status){
						case 1:
							$statusName = 'Aguardando Pagamento';
							break;
						
						case 2:
							$statusName = 'Em an&aacute;lise';
							break;
						
						case 3:
							$statusName = 'Paga';
							break;
						
						case 4:
							$statusName = 'Dispon&iacute;vel';
							break;
						
						case 5:
							$statusName = 'Em Disputa';
							break;
						
						case 6:
							$statusname = 'Devolvida';
							break;
						
						case 7:
							$statusName = 'Cancelada';
							break;
						
						default:
							$statusName = 'Indefinido';
							break;
					}
					$changeTo = $order->getStatus();
					$comment = $statusName.' - Tipo Pagamento: '.$tipoPagamento;
					$order->addStatusToHistory(
						$changeTo,
						$comment,
						$notified = FALSE
					);
				}
				
				
				if( $status == 7 ){
					if( $order->getStatus() != 'canceled' ) $order->cancel()->save();
				}
			}
			$order->save();
			// Envia o e-mail ao receber a confirma??o do PagSeguro
			if( $status == 3 ){ $invoice->sendEmail(); }
			elseif( $order->getStatus() != 'canceled' ){
				$storeId = Mage::app()->getStore()->getStoreId();
				$mailTemplate = Mage::getModel('core/email_template');
				$mailTemplate->setDesignConfig(
					array(
						'area' => 'frontend',
						'store' => $storeId
					)
				);
				$emailId = 3;
				// Envia o e-mail para o cliente
				$mailTemplate->sendTransactional(
					$emailId,
					array( 'email' => $this->getEmailVendas(), 'name' => $this->getEmailVendasNome() ),
					$order->getCustomerEmail(),
					$order->getCustomerName(),
					array( 'order' => $order, 'payment_html' => $order->getPayment()->getMethodInstance()->getTitle() )
				);
				
				// Envia o e-mail para todos cadastrados no admin que devem receber o e-mail de c?pia
				$copyTo = explode( ',', Mage::getStoreConfig('sales_email/invoice/copy_to') );
				foreach( $copyTo as $copy ){
					$mailTemplate->sendTransactional(
						$emailId,
						array( 'email' => $this->getEmailVendas(), 'name' => $this->getEmailVendasNome() ),
						trim($copy),
						trim($copy),
						array( 'order' => $order, 'payment_html' => $order->getPayment()->getMethodInstance()->getTitle() )
					);
				}
			}
			$storeId = Mage::app()->getStore()->getStoreId();
			$mailTemplate = Mage::getModel('core/email_template');
			$mailTemplate->setDesignConfig(
				array(
					'area' => 'frontend',
					'store' => $storeId
				)
			);
			$emailId = 3;
			// Envia o e-mail para o cliente
			$mailTemplate->sendTransactional(
				$emailId,
				array( 'email' => $this->getEmailVendas(), 'name' => $this->getEmailVendasNome() ),
				$order->getCustomerEmail(),
				$order->getCustomerName(),
				array( 'order' => $order, 'payment_html' => $order->getPayment()->getMethodInstance()->getTitle() )
			);
			var_dump($order->getIncrementId());
		}
	}
	
	public function carrinhosAbandonadosAction(){
		if( $this->getRequest()->getParam('demi') == 'divermonti' ){
			//Timezone correto
			date_default_timezone_set('America/Sao_Paulo');
			
			//Vari?veis do cURL
			$cURL = curl_init();
			$initialDate = str_replace( ' ', 'T', date( 'Y-m-d H:i', strtotime("-3 hours -1 minute") ) ); //O script roda a cada 3 horas, por seguran?a adicionamos 1 minuto para o poss?vel delay
			$finalDate = str_replace( ' ', 'T', date( 'Y-m-d H:i', strtotime("-20 minutes") ) );
			$email = $this->getEmailPS();
			$token = $this->getTokenPS();
			$url = 'https://ws.pagseguro.uol.com.br/v2/transactions/abandoned?initialDate='.$initialDate.'&finalDate='.$finalDate.'&page=1&maxPageResults=999&email='.$email.'&token='.$token;
			
			//cURL settings
			curl_setopt($cURL, CURLOPT_URL, $url);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_HEADER, false);
			curl_setopt($cURL, CURLOPT_TIMEOUT, 30);
			curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
			
			//Execu??o
			$resposta = curl_exec($cURL);
			curl_close($cURL);
			
			$xml = new SimpleXMLElement($resposta);
			
			if( isset($xml->transactions) ){ foreach( $xml->transactions->transaction as $transaction ){ Mage::getModel('sales/order')->loadByIncrementId($transaction->reference)->cancel()->save(); } }
		}
	}
	
	public function consultaAction(){
		//Timezone correto
		date_default_timezone_set('America/Sao_Paulo');
		
		//Vari?veis do cURL
		$cURL = curl_init();
		$initialDate = str_replace( ' ', 'T', date( 'Y-m-d H:i', strtotime("-9 days -1 minute") ) ); //O script roda a cada 3 horas, por seguran?a adicionamos 1 minuto para o poss?vel delay
		$finalDate = str_replace( ' ', 'T', date( 'Y-m-d H:i', strtotime("-20 minutes") ) );
		$email = $this->getEmailPS();
		$token = $this->getTokenPS();
		$url = 'https://ws.pagseguro.uol.com.br/v2/transactions?initialDate='.$initialDate.'&finalDate='.$finalDate.'&page=1&maxPageResults=999&email='.$email.'&token='.$token;
		
		//cURL settings
		curl_setopt($cURL, CURLOPT_URL, $url);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_HEADER, false);
		curl_setopt($cURL, CURLOPT_TIMEOUT, 30);
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
		
		//Execu??o
		$resposta = curl_exec($cURL);
		curl_close($cURL);
		
		$xml = new SimpleXMLElement($resposta);
		var_dump( $resposta );
		return;
		if( isset($xml->transactions) ){ foreach( $xml->transactions->transaction as $transaction ){ Mage::getModel('sales/order')->loadByIncrementId($transaction->reference)->cancel()->save(); } }
	}
	
	public function trocarStatusAction(){
		$order = Mage::getModel('sales/order')->loadByIncrementId(100000346);
		$changeTo = Mage_Sales_Model_Order::STATE_COMPLETE;
		$comment = '';
		$order->addStatusToHistory(
			$changeTo,
			$comment,
			$notified = TRUE
		);
		$order->save();
	}
	
	public function testeOrderAction(){
		echo Mage::getModel('sales/order')->loadByIncrementId(100000302)->getStatus();
	}
	
	private function getEmailPS(){
		return Mage::getStoreConfig('payment/pagseguro_standard/emailID');
	}
	
	private function getTokenPS(){
		return Mage::getStoreConfig('payment/pagseguro_standard/token');
	}
	
	private function getEmailVendas(){
		return Mage::getStoreConfig('trans_email/ident_sales/email');
	}
	
	private function getEmailVendasNome(){
		return Mage::getStoreConfig('trans_email/ident_sales/name');
	}
}