<?php
/**
 * Codesheep_Cielo
 *
 * Main controller for payment using Cielo
 * this handles all orders and sends a request to Cielo gateway
 *
 * @author       Samir J M Araujo
 * @authorEmail  samir.araujo@18digital.com.br
 * @support		 suporte@18digital.com.br
 * @dependencies Mage_Core_Controller_Front_Action
 * @category     Codesheep
 * @package		 Codesheep_Cielo
 * @version		 1.1.0
 *
 */
class Codesheep_Cielo_StandardController extends Mage_Core_Controller_Front_Action{
	/**
	 * Order instance
	 */
	protected $_order;

	/**
	 *  Get order
	 *
	 *  @return	  Mage_Sales_Model_Order
	 */
	public function getOrder()
	{
		if ($this->_order == null) {
					$this->_order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
		}
		return $this->_order;
	}

	/**
	 * Send expire header to ajax response
	 *
	 */
	protected function _expireAjax()
	{
		if (!Mage::getSingleton('checkout/session')->getQuote()->hasItems()) {
			$this->getResponse()->setHeader('HTTP/1.1','403 Session Expired');
			exit;
		}
	}

	/**
	 * Get singleton with paypal strandard order transaction information
	 *
	 * @return Mage_Paypal_Model_Standard
	 */
	public function getStandard()
	{
		return Mage::getSingleton('cielo/standard');
	}

	/**
	 * When a customer chooses Paypal on Checkout/Payment page
	 *
	 */
	public function redirectAction()
	{


		$session = Mage::getSingleton('checkout/session');
//	        $session->setPaypalStandardQuoteId($session->getQuoteId());

		$order = $this->getOrder();
		$payment = $order->getPayment();
		$bandeiras = array('VI' => 'visa', 'MC' => 'mastercard', 'AE' => 'amex', 'EL' => 'elo', 'DI' => 'discover', 'DC' => 'diners');

		Mage::log('[Codesheep_Cielo::redirectAction] Order: '.$order->getId());

		// Verify if this order was already sent
		if( $payment->getCieloRequestFlag() ){
			Mage::log('[Codesheep_Cielo::redirectAction] Order already sent: '.$order->getId());

			$additionaldata = unserialize($payment->getData('additional_data'));
			if( $additionaldata['status'] == 4 || $additionaldata['status'] == 6 ) $url = Mage::getUrl('cielo/standard/success');
			else $url = Mage::getUrl('checkout/onepage/failure');

			$session->setRedirectUrl($url);

			$this->getResponse()->setBody($this->getLayout()->createBlock('cielo/standard_redirect')->toHtml());
			$session->unsQuoteId();
			$session->unsRedirectUrl();

			return;
		}

		$cielo = Mage::getModel('cielo/cielo');

		$cielo->sendRequestFlag = $payment->getCieloRequestFlag();
		$cielo->ambiente = Mage::getStoreConfig('payment/cielo/environment');

		$cielo->formaPagamentoBandeira = $bandeiras[$payment->getData('cc_type')];
		$additionaldata = unserialize($payment->getData('additional_data'));

		if($additionaldata["cc_parcelas"] > 1){
			$cielo->formaPagamentoProduto = Mage::getStoreConfig('payment/cielo/parcelamento');
			$cielo->formaPagamentoParcelas = $additionaldata["cc_parcelas"];
		}else{
			$cielo->formaPagamentoProduto = $additionaldata["cc_parcelas"];
			$cielo->formaPagamentoParcelas = 1;
		}

		$cielo->dadosEcNumero = Mage::getStoreConfig('payment/cielo/merchant_id');
		$cielo->dadosEcChave = Mage::getStoreConfig('payment/cielo/merchant_key');


		$cielo->capturar = Mage::getStoreConfig('payment/cielo/captura') ? 'true' : 'false';
		$cielo->autorizar = "3";

		$cielo->dadosPortadorNumero = $payment->decrypt($payment->getCcNumberEnc());
		$cielo->dadosPortadorVal = $payment->getCcExpYear().str_pad($payment->getCcExpMonth(), 2, "0", STR_PAD_LEFT);

		if(!$additionaldata['cc_cid_enc']){
			$cielo->dadosPortadorInd = "0";
		}elseif($bandeiras[$payment->getData('cc_type')] == 'mastercard'){
			$cielo->dadosPortadorInd = "1";
		}else{
			$cielo->dadosPortadorInd = "1";
		}

		$cielo->dadosPortadorCodSeg = $payment->decrypt($additionaldata['cc_cid_enc']);
		$cielo->dadosPedidoNumero = Mage::getSingleton('checkout/session')->getLastOrderId();
		$cielo->dadosPedidoValor = number_format($order->getGrandTotal(),2,'','');
//					$cielo->urlRetorno = Mage::getUrl('cielo/standard/success');



		$cieloResposta = $cielo->RequisicaoTid();

		$cielo->tid = $cieloResposta->tid;
		$cielo->pan = $cieloResposta->pan;
		$cielo->status = $cieloResposta->status;

		$additionaldata = unserialize($payment->getData('additional_data'));
		$additionaldata['tid'] = (string)$cieloResposta->tid;

		if($cieloResposta->mensagem){
			$additionaldata['erro'] = array('codigo' => (string)$cieloResposta->codigo, 'mensagem' => (string)$cieloResposta->mensagem);
		}



		$cieloResposta = $cielo->RequisicaoAutorizacaoPortador();

		$cielo->tid = $cieloResposta->tid;
		$cielo->pan = $cieloResposta->pan;
		$cielo->status = $cieloResposta->status;

		if($cieloResposta->mensagem){
			$additionaldata['erro'] = !isset($additionaldata['erro']) ? array('codigo' => (string)$cieloResposta->codigo, 'mensagem' => (string)$cieloResposta->mensagem) : $additionaldata['erro'];
		}else{
			$additionaldata['tid'] = (string)$cieloResposta->tid;
		}

		$additionaldata['status'] = (string)$cieloResposta->status;
		if($cieloResposta->autorizacao){
			$additionaldata['autorizacao'] = array(
					'codigo' => (string)$cieloResposta->autorizacao->codigo,
					'mensagem' => (string)$cieloResposta->autorizacao->mensagem
			);
		}
		if($cieloResposta->captura){
			$additionaldata['captura'] = array(
					'codigo' => (string)$cieloResposta->captura->codigo,
					'mensagem' => (string)$cieloResposta->captura->mensagem
			);
		}

				$payment->setCieloRequestFlag('1');
				$payment->setAdditionalData(serialize($additionaldata));
				$payment->save();

				if($cielo->status == '4' || $cielo->status == '6'){
					$url = Mage::getUrl('cielo/standard/success');

					// Update ClearSale data
					try{
						Mage::helper('clearsale')->resendOrder($order->getId());
					}catch(Exception $e){
						Mage::log('[Codesheep_Cielo_StandardController::redirectAction] '.$e->getMessage());
					}
				}else{
					//$order->setStatus( 'canceled', true );
					$order->cancel();
					$order->save();
					$url = Mage::getUrl('checkout/onepage/failure');

					/*Sends an e-mail to the client*/
					try{ $order->sendNewOrderEmail(); }catch(Exception $ex){}
				}
				$session->setRedirectUrl($url);

		$this->getResponse()->setBody($this->getLayout()->createBlock('cielo/standard_redirect')->toHtml());
		$session->unsQuoteId();
		$session->unsRedirectUrl();
	}

		public function capturaAction(){
			$orderId = $this->getRequest()->getParam('order_id');
			$order = Mage::getModel('sales/order')->load($orderId);
			$payment = $order->getPayment();
			$additionaldata = unserialize($payment->getData('additional_data'));
			$tid = $additionaldata["tid"];
			$loja = Mage::getStoreConfig('payment/cielo/merchant_id');
			$chave = Mage::getStoreConfig('payment/cielo/merchant_key');
			$valor = number_format($order->getGrandTotal(),2,'','');

			$cielo = Mage::getModel('cielo/cielo');
			$cielo->ambiente = Mage::getStoreConfig('payment/cielo/environment');
			$objResposta = $cielo->RequisicaoCaptura($tid, $loja, $chave, $valor);

			if($objResposta->captura){
				$additionaldata['captura'] = array(
						'codigo' => (string)$objResposta->captura->codigo,
						'mensagem' => (string)$objResposta->captura->mensagem
				);

				$payment->setAdditionalData(serialize($additionaldata));
				$payment->save();

				$codigo = (string)$objResposta->captura->codigo;
				$mensagem = (string)$objResposta->captura->mensagem;
			}else{
				if($objResposta->codigo){
					$codigo = (string)$objResposta->codigo;
					$mensagem = (string)$objResposta->mensagem;
				}
			}

			$body = '<html><head><title>Capturar Transação</title></head>'.
				'<body onunload="opener.location.reload();">' .
				'<h1>Transa&ccedil;&atilde;o de Captura</h1><br /><br />' .
				'c&oacute;digo: '.$codigo.'<br />'.
				'mensagem: '.$mensagem.'<br />'.
				'<br /><br /><br /><br />'.
				'<button onclick="window.close();">Fechar Janela</button>';

		$this->getResponse()->setBody($body);
		}


	/**
	 * When a customer cancel payment from paypal.
	 */
	public function cancelAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setQuoteId($session->getPaypalStandardQuoteId(true));
		if ($session->getLastRealOrderId()) {
			$order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
			if ($order->getId()) {
				$order->cancel()->save();
			}
		}
		$this->_redirect('checkout/cart');
	}

	/**
	 * when paypal returns
	 * The order information at this point is in POST
	 * variables.  However, you don't want to "process" the order until you
	 * get validation from the IPN.
	 */
	public function  successAction()
	{
		$session = Mage::getSingleton('checkout/session');

		/*Sends an e-mail to the client*/
		$order = new Mage_Sales_Model_Order();
		$incrementId = Mage::getSingleton( 'checkout/session' )->getLastRealOrderId();
		$order->loadByIncrementId( $incrementId );
		try{ $order->sendNewOrderEmail(); }catch(Exception $ex){}

//	        $session->setQuoteId($session->getPaypalStandardQuoteId(true));
		Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
		$this->_redirect('checkout/onepage/success', array('_secure'=>true));
	}
}
