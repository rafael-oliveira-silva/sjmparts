<?php

class Codesheep_Cielo_Block_Risco extends Mage_Payment_Block_Info_Ccsave
{
	public $_productionUrl    = 'http://www.clearsale.com.br/integracaov2/freeclearsale/frame.aspx';
	public $_homologUrl       = 'http://homologacao.clearsale.com.br/integracaov2/freeclearsale/frame.aspx';
	public $_mode 		      = '';
	public $_codigoIntegracao = '';
	public $_debug			  = '';
	
    protected function _construct()
    {
        parent::_construct();
		
		if( Mage::getStoreConfig('payment/risco/active') ){
			if( Mage::getStoreConfig('payment/risco/empresa') == 'fcontrol' ) $this->setTemplate('cielo/risco/fcontrol.phtml');
			else $this->setTemplate('cielo/risco/clearsale.phtml');
		} else $this->setTemplate('cielo/risco/blank.phtml');
    }


		public function getOrder(){
			return Mage::registry('current_order');
		}

		public function getFcontrolUrl(){

			$order = Mage::registry('current_order');
			if($order){
				$items = $order->getAllItems();			
				$billing = $order->getBillingAddress();
				$shipping = $order->getShippingAddress();
			
				$additionaldata = unserialize($order->getPayment()->getData('additional_data'));			
				$parcelas = isset($additionaldata["cc_parcelas"]) ? $additionaldata["cc_parcelas"] : 1;

			
				$args = 'login='.Mage::getStoreConfig('payment/risco/usuario').'&senha='.Mage::getStoreConfig('payment/risco/senha');
				$args = $args.'&dataCompra='. $order->getCreatedAtStoreDate();
				$args = $args.'&codigoPedido=' . $order->getRealOrderId();
				$args = $args.'&ip='.$order->getRemoteIp();
				$args = $args.'&nomeComprador='.$this->preparaCampo($billing->getFirstname().' '.$billing->getLastname());
				$args = $args.'&ruaComprador='.$this->preparaCampo($this->getEndereco($billing->getStreet(1)));
				$args = $args.'&numeroComprador='.$this->preparaCampo($billing->getStreet(2));
				$args = $args.'&complementoComprador='.$this->preparaCampo($billing->getStreet(3));
				$args = $args.'&bairroComprador='.$this->preparaCampo($billing->getStreet(4));
				$args = $args.'&cidadeComprador='.$this->preparaCampo($billing->getCity());
				$args = $args.'&ufComprador='.$this->preparaCampo($billing->getRegion());
				$args = $args.'&cepComprador='.$this->preparaCampo(str_replace('-','', $billing->getPostcode()));
				$args = $args.'&paisComprador='.$this->preparaCampo($billing->getCountry() . 'A');
				$args = $args.'&dddComprador='.$this->preparaCampo($this->getDDD($billing->getTelephone()));			
				$args = $args.'&telefoneComprador='.$this->preparaCampo($this->getNumeroFone($billing->getTelephone()));
	//			$args = $args.'&shopper_cel_phone=';
				$args = $args.'&emailComprador='.$this->preparaCampo($order->getCustomerEmail());			
				$args = $args.'&cpfComprador='.$this->preparaCampo($order->getCustomerTaxvat());
				$args = $args.'&nomeEntrega='.$this->preparaCampo($shipping->getFirstname().' '.$shipping->getLastname());
				$args = $args.'&ruaEntrega='.$this->preparaCampo($this->getEndereco($shipping->getStreet(1)));
				$args = $args.'&numeroEntrega='.$this->preparaCampo($shipping->getStreet(2));
				$args = $args.'&complementoEntrega='.$this->preparaCampo($shipping->getStreet(3));
				$args = $args.'&bairroEntrega='.$this->preparaCampo($shipping->getStreet(4));
				$args = $args.'&cidadeEntrega='.$this->preparaCampo($shipping->getCity());
				$args = $args.'&ufEntrega='.$this->preparaCampo($shipping->getRegion());
				$args = $args.'&cepEntrega='.$this->preparaCampo(str_replace('-','', $shipping->getPostcode()));
				$args = $args.'&paisEntrega='.$this->preparaCampo($shipping->getCountry() . 'A');
				$args = $args.'&items_dif='.$this->getItensDiferentes($items);
				$args = $args.'&items_tot='.$this->getItensQuantidade($items);
				$args = $args.'&total='.number_format($order->getBaseGrandTotal(), 2, '', '');
	//			$args = $args.'&num_parcelas='.$order->getPayment()->getCcParcelas();
				$args = $args.'&num_parcelas='.$parcelas;		
				$args = $args.'&payment_method_id='.$this->getPaymentMethodId($order->getPayment()->getCcType());
						
			
			
				/*
						
	//			$args = $args.'&shopper_ddd_phone='.$this->preparaCampo($this->clienteDDD);
				$args = $args.'&shopper_phone='.$this->preparaCampo($this->clienteFone);
				$args = $args.'&shopper_ddd_cel_phone=';
				$args = $args.'&shopper_cel_phone=';
				$args = $args.'&shopper_email='.$this->preparaCampo($this->clienteEmail);

				$args = $args.'&shopper_password=';
				$args = $args.'&ship_to_name='.$this->preparaCampo($this->destinoNome);
				$args = $args.'&ship_to_street='.$this->preparaCampo($this->destinoEndereco);
				$args = $args.'&ship_to_street_number='.$this->preparaCampo($this->destinoEnderecoNumero);
				$args = $args.'&ship_to_street_compl='.$this->preparaCampo($this->destinoEnderecoCompl);
				$args = $args.'&ship_to_street_district='.$this->preparaCampo($this->destinoBairro);
				$args = $args.'&ship_to_city='.$this->preparaCampo($this->destinoCidade);
				$args = $args.'&ship_to_state='.$this->preparaCampo($this->destinoEstado);
				$args = $args.'&ship_to_zip='.$this->preparaCampo($this->destinoCep);
				$args = $args.'&ship_to_country='.$this->preparaCampo($this->destinoPais);
				$args = $args.'&ship_to_ddd_phone='.$this->preparaCampo($this->destinoDDD);
				$args = $args.'&ship_to_phone='.$this->preparaCampo($this->destinoFone);
				$args = $args.'&ship_to_ddd_cel_phone=';
				$args = $args.'&ship_to_cel_phone=';
				$args = $args.'&items_dif='.$this->itensDiferentes;
				$args = $args.'&items_tot='.$this->itensQuantidade;
				$args = $args.'&total='.$this->total;
				$args = $args.'&num_parcelas='.$this->parcelas;
				$args = $args.'&payment_method_id='.$this->payment_method_id; */
			
				return $args;
			}
			return false;
		}

		protected function preparaCampo($campo) {
			$enc = mb_detect_encoding ($campo);
			if ($enc != 'ISO-8859-1' && $enc != 'ASCII') { //se n�o for ISO, converte para ISO
				$campo = mb_convert_encoding($campo, 'ISO-8859-1', 'auto');
			}
			$campo = str_replace("\n", '', $campo); //retira quebras de linha
			$campo = urlencode($campo); //codifica entidades HTML. Isso evita que o usu�rio digite algum comando HTML que interfira na p�gina
			$campo = str_replace(')', '&#41;', $campo); //codifica parentesis. O sistema do bradesco usa parentesis nos comandos
			$campo = str_replace('(', '&#40;', $campo); //codifica parentesis. O sistema do bradesco usa parentesis nos comandos
			return($campo);
		}	
		
		protected function getEndereco($endereco) {
			//procura por v�rgula ou tra�o para achar o final do logradouro
	    	$posSeparador = $this->getPosSeparador($endereco, false);  
			if ($posSeparador !== false) {
				$endereco = trim(substr($endereco, 0, $posSeparador));
			}

			return($endereco);
		}		
		
		protected function getNumEndereco($endereco) {
	    	$numEndereco = '';

	    	//procura por virgula ou tra�o para achar o final do logradouro
	    	$posSeparador = $this->getPosSeparador($endereco, false);  
			if ($posSeparador !== false) {
				$numEndereco = trim(mb_substr($endereco, $posSeparador + 1));
			}

			//procura por v�rgula, tra�o ou espa�o para achar o final do n�mero da resid�ncia
			$posComplemento = $this->getPosSeparador($numEndereco, true);
			if ($posComplemento !== false) {
				$numEndereco = trim(mb_substr($numEndereco, 0, $posComplemento));
			}

			/*if ($numEndereco == '') {
				$numEndereco = '?';
			}*/

			return($numEndereco);
		}

		protected function getPosSeparador($endereco, $procuraEspaco = false) {
			$posSeparador = strpos($endereco, ',');
			if ($posSeparador === false) {
			  $posSeparador = strpos($endereco, '-');
			}

			if ($procuraEspaco) {	  
				if ($posSeparador === false) {
				  $posSeparador = strrpos($endereco, ' ');
				}
			}

			return($posSeparador);
		}

		protected function getDDD($fone) {
			$fone = trim($fone);

	        if (substr($fone, 0, 1) == '0') {
				$fone = substr($fone, 1);
			}

			$cust_ddd = '00';    	
	        $cust_telephone = str_replace('-','',str_replace(')','',str_replace('(','',$fone)));
	        $st = strlen($cust_telephone)-8;
	        if ($st>0) { // in case this string is longer than 8 characters
	            $cust_ddd = substr($cust_telephone, 0, 2);
	        }

			return($cust_ddd);
		}

		protected function getNumeroFone($fone) {
	        $fone = trim($fone);

			$cust_telephone = str_replace('-','',str_replace(')','',str_replace('(','',$fone)));
	        $st = strlen($cust_telephone)-8;
	        if ($st > 0) { // in case this string is longer than 8 characters
				$cust_telephone = substr($cust_telephone, $st, 8);
	        }

			return($cust_telephone);
		}		
		
		protected function getItensDiferentes($items) {
	        return(count($items));
		}

		protected function getItensQuantidade($items) {
			$qty = 0;
			foreach($items as $item) {
				$qty = $qty + $item->getQtyOrdered();
			}
			return($qty);
		}		
		
		private function getPaymentMethodId($tipoCartao) {
			$ret = 1;
	    $tipoCartao = strtoupper($tipoCartao);
			if ($tipoCartao == 'VI' || $tipoCartao == 'VE' || $tipoCartao == 'VISA' || $tipoCartao == 'VISA ELECTRON') {
				$ret = 2;
			}
			else if ($tipoCartao == 'MA' || $tipoCartao == 'MC' || $tipoCartao == 'MASTERCARD' || $tipoCartao == 'MASTER CARD') {
				$ret = 3;
			}
			else if ($tipoCartao == 'DI' || $tipoCartao == 'DINERS' || $tipoCartao == 'DINNERS') {
				$ret = 4;
			}
			else if ($tipoCartao == 'AM' || $tipoCartao == 'AMEX' || $tipoCartao == 'AMERICAN' || $tipoCartao == 'AMERICAN EXPRESS') {
				$ret = 5;
			}
			return($ret);
		}
		
		/*************************************** ClearSale ***************************************/
		/**
		 * @method sendClearSaleRequest
		 *
		 * Retrieves an iframe with ClearSale control panel
		 *
		 * @return html
		 */
		public function getClearSale(){
			$pedido = Mage::getModel('sales/order')->loadByIncrementId($this->getOrder()->getIncrementId());
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
			$tipoPagamento = 1;
			$parcelas = $additional['cc_parcelas'];
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
			$url .= '&TipoCartao='.$bandeira;
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