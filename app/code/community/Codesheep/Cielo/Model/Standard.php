<?php
 
class Codesheep_Cielo_Model_Standard extends Mage_Payment_Model_Method_Cc
{ 
	protected $_code 					= 'cielo';
	protected $_formBlockType 			= 'cielo/form_cc';	
	protected $_infoBlockType 			= 'cielo/info';
	protected $_canUseInternal          = false;
	protected $_canUseForMultishipping  = false;
	protected $_isGateway				= true;
	protected $_canCapture				= true;
	protected $_canRefund				= true;
	protected $_canVoid                 = true;
	protected $_canSaveCc 				= true;
	protected $_canAuthorize			= true;
	protected $_canUseCheckout			= true;

 	public function assignData($data) {
        $details = array();
        if(!($data instanceof Varien_Object)){  $data = new Varien_Object($data); }

        $info = $this->getInfoInstance();
		$additionaldata = array(
								'cc_parcelas'   => $data->getCcParcelas(),
								'cc_cid_enc'    => $info->encrypt($data->getCcCid()),
								'cc_type'	    => $data->getCcType(),
								'cc_owner'	    => $data->getCcOwner(),
								'cc_exp_month'  => $data->getCcExpMonth(),
								'cc_exp_year'   => $data->getCcExpYear(),
								'cc_number_enc' => $info->encrypt($data->getCcNumber()),
								'cc_last_4'		=> substr($data->getCcNumber(), -4)
						);
		$info->setAdditionalData(serialize($additionaldata)); 
		$info->setCcType($data->getCcType());
		$info->setCcOwner($data->getCcOwner());				
		$info->setCcExpMonth($data->getCcExpMonth());
		$info->setCcExpYear($data->getCcExpYear());				
        $info->setCcNumberEnc($info->encrypt($data->getCcNumber()));
        $info->setCcCidEnc($info->encrypt($data->getCcCid()));
		$info->setCcLast4(substr($data->getCcNumber(), -4));
        return $this;
	}
	
	/**
	 * When a customer chooses Paypal on Checkout/Payment page
	 *
	 */
	/*public function authorize(Varien_Object $payment, $amount){
		$order = Mage::getModel('sales/order')->loadByIncrementId($payment->getOrder()->getIncrementId());
		$orderPayment = $order->getPayment();
		$info = $this->getInfoInstance();
		
		$bandeiras = array('VI' => 'visa', 'MC' => 'mastercard', 'AE' => 'amex', 'EL' => 'elo', 'DI' => 'discover', 'DC' => 'diners');

		$cielo = Mage::getModel('cielo/cielo');

		$cielo->ambiente = Mage::getStoreConfig('payment/cielo/environment');

		$cielo->formaPagamentoBandeira = $bandeiras[$orderPayment->getData('cc_type')];
		$additionaldata = unserialize($orderPayment->getData('additional_data'));

		if($additionaldata['cc_parcelas'] > 1){
			$cielo->formaPagamentoProduto = Mage::getStoreConfig('payment/cielo/parcelamento');
			$cielo->formaPagamentoParcelas = $additionaldata['cc_parcelas'];
		}else{
			$cielo->formaPagamentoProduto = $additionaldata['cc_parcelas'];
			$cielo->formaPagamentoParcelas = 1;
		}

		$cielo->dadosEcNumero = Mage::getStoreConfig('payment/cielo/merchant_id');
		$cielo->dadosEcChave = Mage::getStoreConfig('payment/cielo/merchant_key');


		$cielo->capturar = Mage::getStoreConfig('payment/cielo/captura') ? 'true' : 'false';
		$cielo->autorizar = '3';

		$cielo->dadosPortadorNumero = $orderPayment->decrypt($orderPayment->getCcNumberEnc());
		$cielo->dadosPortadorVal = $orderPayment->getCcExpYear().str_pad($orderPayment->getCcExpMonth(), 2, '0', STR_PAD_LEFT);

		if(!$additionaldata['cc_cid_enc']) $cielo->dadosPortadorInd = '0';
		elseif($bandeiras[$orderPayment->getData('cc_type')] == 'mastercard') $cielo->dadosPortadorInd = '1';
		else $cielo->dadosPortadorInd = '1';

		$cielo->dadosPortadorCodSeg = $orderPayment->decrypt($additionaldata['cc_cid_enc']);
		$cielo->dadosPedidoNumero = $order->getId();
		$cielo->dadosPedidoValor = number_format($order->getGrandTotal(),2,'','');
		
		$cieloResposta = $cielo->RequisicaoTid();
		
		$cielo->tid = $cieloResposta->tid;
		//$cielo->pan = $cieloResposta->pan;
		//$cielo->status = $cieloResposta->status;
		
		//$additionaldata = unserialize($payment->getData('additional_data'));
		
		if(isset($cieloResposta->mensagem)) $additionaldata['erro'] = array('codigo' => (string)$cieloResposta->codigo, 'mensagem' => (string)$cieloResposta->mensagem);
		else $additionaldata['tid'] = (string) $cielo->tid;
		
		$additionaldata['tid'] = (string) $cielo->tid;
		
		$cieloResposta = $cielo->RequisicaoAutorizacaoPortador();
		
		// Mage::log('Cielo: Requisiчуo Autorizaчуo Portador: '.Zend_Debug::dump($cieloResposta));
		
		$cielo->tid = $cieloResposta->tid;
		$cielo->pan = $cieloResposta->pan;
		$cielo->status = $cieloResposta->status;
		
		if($cieloResposta->mensagem) $additionaldata['erro'] = !isset($additionaldata['erro']) ? array('codigo' => (string)$cieloResposta->codigo, 'mensagem' => (string)$cieloResposta->mensagem) : $additionaldata['erro'];
		else $additionaldata['tid'] = (string)$cieloResposta->tid;
		
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
		
		$info->setAdditionalData(serialize($additionaldata));
		$info->save();
		
		//$orderPayment->setAdditionalData(serialize($additionaldata));
		//$orderPayment->save();
		
		//Mage::log('Order Additional Data - '.Zend_Debug::dump(unserialize($orderPayment->getData('additional_data'))));
		
		if($cielo->status != '4' && $cielo->status != '6'){
			$order->cancel();
			$order->save();
		}
		
		return $this;
	}*/

	/**
	* Return Order place redirect url
	*
	* @return string
	*/
	public function getOrderPlaceRedirectUrl()
	{
	return Mage::getUrl('cielo/standard/redirect', array('_secure' => true));
	}
 
  //  define a url do pagseguro
  public function getCieloUrl()
  {
	if(Mage::getStoreConfig('payment/cielo/environment')){
      	$url = 'https://ecommerce.cbmp.com.br/servicos/ecommwsec.do';
	}else{
		$url = 'https://qasecommerce.cielo.com.br/servicos/ecommwsec.do';
	}
    return $url;
  }
  
  
  
    /**
     * Metodo realiza o post do xml local para a visa
     * @param String Xml com os dados do pedido
     * @return mixed
     */
    function file_post_contents($msg) {
        $postdata = http_build_query(array('mensagem' => $msg));
 
        $opts = array('http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
 
        $context = stream_context_create($opts);
        return file_get_contents($this->getCieloUrl(), false, $context);
    }  

}

?>