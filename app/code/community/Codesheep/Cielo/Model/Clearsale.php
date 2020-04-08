<?php

class Codesheep_Cielo_Model_Clearsale extends Mage_Core_Model_Abstract{
	
	public $_productionUrl    = 'http://www.clearsale.com.br/integracaov2/freeclearsale/frame.aspx';
	public $_homologUrl       = 'http://homologacao.clearsale.com.br/integracaov2/freeclearsale/frame.aspx';
	public $_mode 		      = '';
	public $_codigoIntegracao = '';
	public $_debug			  = '';
	
	/*************************************** ClearSale ***************************************/
	/**
	 * @method sendClearSaleRequest
	 *
	 * Retrieves an iframe with ClearSale control panel
	 *
	 * @return html
	 */
	public function getClearSale($paymentType = 1, $orderId = NULL){
		if( empty($orderId) && !method_exists($this, 'getOrder') ) return;
		$orderId = empty($orderId) ? $this->getOrder()->getIncrementId() : $orderId;
		$pedido = Mage::getModel('sales/order')->loadByIncrementId($orderId);
		$billing = Mage::getModel('sales/order_address')->load($pedido->getBillingAddress()->getId());
		$shipping = Mage::getModel('sales/order_address')->load($pedido->getShippingAddress()->getId());
		$streetBilling = $billing->getStreet();
		$shippingStreet = $shipping->getStreet();
		$items = $pedido->getAllVisibleItems();
		
		// Sources
		$bandeiras = array(
			'DC' => 1,
			'MC' => 2,
			'VI' => 3,
			'EL' => 4,
			'DI' => 4,
			'AE' => 5
		);
		
		// Changes
		$data = $pedido->getCreatedAtDate();
		$dateE = explode( '/', substr($data, 0, 10) );
		$payment = $pedido->getPayment();
		$additional = unserialize( $pedido->getPayment()->getAdditionalData() );
		
		// ID do Pedido
		$pedidoId = $pedido->getIncrementId();
		// $date = $dateE[2].'-'.$dateE[1].'-'.$dateE[0].' '.substr( $data, 11 );
		$date = $data;
		$ip = $pedido->getRemoteIp();
		$total = number_format( $pedido->getBaseGrandTotal(), 2, '.', '' );
		$tipoPagamento = $paymentType;
		$parcelas = isset( $additional['cc_parcelas'] ) ? $additional['cc_parcelas'] : 1;
		$bandeira = $bandeiras[$payment->getCcType()];
		$cobranca = array(
			'Cobranca_Nome'                   => $payment->getCcOwner(),
			'Cobranca_Email'                  => $pedido->getCustomerEmail(),
			'Cobranca_Documento'              => str_replace( '-', '', str_replace('.', '', $pedido->getCustomerTaxvat()) ),
			'Cobranca_Logradouro'             => $streetBilling[0],
			'Cobranca_Logradouro_Numero'      => $streetBilling[1],
			'Cobranca_Logradouro_Complemento' => $streetBilling[2],
			'Cobranca_Bairro'                 => $streetBilling[3],
			'Cobranca_Cidade'				  => $billing->getCity(),
			'Cobranca_Estado'				  => $billing->getRegion(),
			'Cobranca_CEP'					  => str_replace( '-', '', $billing->getPostcode() ),
			'Cobranca_Pais'					  => $billing->getCountry(),
			'Cobranca_DDD_Telefone'			  => substr($billing->getTelephone(), 1, 2),
			'Cobranca_Telefone'				  => str_replace( '-', '', substr($billing->getTelephone(), 4) )
		);
		$entrega = array(
			'Entrega_Nome'                    => $payment->getCcOwner(),
			'Entrega_Email'                   => $pedido->getCustomerEmail(),
			'Entrega_Documento'               => str_replace( '-', '', str_replace('.', '', $pedido->getCustomerTaxvat()) ),
			'Entrega_Logradouro'              => $shippingStreet[0],
			'Entrega_Logradouro_Numero'       => $shippingStreet[1],
			'Entrega_Logradouro_Complemento'  => $shippingStreet[2],
			'Entrega_Bairro'                  => $shippingStreet[3],
			'Entrega_Cidade'				  => $shipping->getCity(),
			'Entrega_Estado'				  => $shipping->getRegion(),
			'Entrega_CEP'					  => str_replace( '-', '', $shipping->getPostcode() ),
			'Entrega_Pais'					  => $shipping->getCountry(),
			'Entrega_DDD_Telefone'			  => substr($shipping->getTelephone(), 1, 2),
			'Entrega_Telefone'				  => str_replace( '-', '', substr($shipping->getTelephone(), 4) )
		);
		
		$i = 1;
		
		foreach( $items as $item ){
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
			$productCats = $product->getCategoryCollection()->getAllIds();
			$categoryName = Mage::getModel('catalog/category')->load($productCats[0])->getName();
			
			$itemsQuote[] = array(
				'Item_ID_'.$i        => $product->getId(),
				'Item_Nome_'.$i      => $product->getName(),
				//'Item_Qtd_'.$i       => $item->getQty(),
				'Item_Valor_'.$i     => number_format( $product->getFinalPrice(), 2, '.', '' ),
				'Item_Categoria_'.$i => $categoryName
			);
			
			$i++;
		}
		
		// User info
		$url  = $this->getClearSaleUrl().'?';
		$url .= 'CodigoIntegracao='.$this->getCodigoIntegracao();
		$url .= '&PedidoID='.$pedidoId;
		$url .= '&Data='.$date;
		$url .= '&IP='.$ip;
		$url .= '&Total='.$total;
		$url .= '&TipoPagamento='.$tipoPagamento;
		$url .= !empty($bandeira) ? '&TipoCartao='.$bandeira : '';
		$url .= '&Parcelas='.$parcelas;
		
		// Cobrança
		foreach( $cobranca as $cKey => $cValue ) $url .= '&'.$cKey.'='.$cValue;
		
		// Entrega
		foreach( $entrega as $eKey => $eValue ) $url .= '&'.$eKey.'='.$eValue;
		
		// Itens
		foreach( $itemsQuote as $itemQuote ) foreach( $itemQuote as $iKey => $iValue ) $url .= '&'.$iKey.'='.$iValue;
		
		$html = '<iframe src="'.$url.'" width="280" height="85" frameborder="0" scrolling="no">Your browser doens\'t allow iframes</iframe>';
		
		$this->_debug = $url;
		
		return $html;
		
	}
	
	public function debugClearSale(){
		return $this->_debug;
	}
	
	/**
	 * @method getClearSaleHomologUrl
	 * 
	 * Get ClearSale homolog URL set as a public variable
	 * 
	 * @return this::_homologUrl
	 */
	public function getClearSaleHomologUrl(){
		return $this->_homologUrl;
	}
	
	/**
	 * @method getClearSaleProductionUrl
	 * 
	 * Get ClearSale production URL set as a public variable
	 * 
	 * @return this::_productionUrl
	 */
	public function getClearSaleProductionUrl(){
		return $this->_productionUrl;
	}
	
	/**
	 * @method getClearSaleUrl
	 * 
	 * Get ClearSale URL based on admin choice
	 * 
	 * @return url
	 */
	public function getClearSaleUrl(){
		if( $this->getMode() == 0 ) return $this->getClearSaleHomologUrl();
		elseif( $this->getMode() == 1 ) return $this->getClearSaleProductionUrl();
	}
	
	/**
	 * @method getMode
	 * 
	 * Get ClearSale mode set in admin area
	 * 
	 * @return this::_mode
	 */
	protected function getMode(){
		if( !empty($this->_mode) ) return $this->_mode;
		
		$this->_mode = Mage::getStoreConfig('payment/risco/modo');
		
		return $this->_mode;
	}
	
	/**
	 * @method getCodigoIntegracao
	 * 
	 * Get ClearSale integration code in order to validate the request
	 * 
	 * @return this::_codigoIntegracao
	 */
	protected function getCodigoIntegracao(){
		if( !empty($this->_codigoIntegracao) ) return $this->_codigoIntegracao;
		
		$this->_codigoIntegracao = Mage::getStoreConfig('payment/risco/codigo');
		
		return $this->_codigoIntegracao;
	}
}