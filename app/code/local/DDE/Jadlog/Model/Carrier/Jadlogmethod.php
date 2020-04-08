<?php

class DDE_Jadlog_Model_Carrier_Jadlogmethod extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code  		= 'jadlogmethod';
    protected $_items 		= '';
    protected $_modalidades = '';
    protected $_destZipCode = '';
    protected $_result		= null;
    protected $_freeShipping = false;
    protected $_freeShippingMethod = null;

    /**
     * @method isTrackingAvailable
     * @return TRUE
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Get Tracking Info
     *
     * @param mixed $tracking
     * @return mixed
     */
    public function getTrackingInfo($tracking)
    {
        $request = Mage::getModel('jadlogmethod/tracker_request');
        $progresso = $request->send($tracking);
        // $progresso = TRUE;

        if ($progresso !== false) {
            $_progressDetail = array();

            foreach ($progresso as $prog) {
                list($_deliveryDate, $_deliveryTime) = $this->_getArrayDeliveryDateTime($prog['data']);

                $descricao = $prog['status'];
                $descricao .= isset($prog['descricao']) ? ' - ' . utf8_encode($prog['descricao']) : '';

                $_progressDetail[] = array(
                    'deliverydate' => $_deliveryDate,
                    'deliverytime' => $_deliveryTime,
                    'deliverylocation' => $prog['localizacao'],
                    'activity' => $descricao
                );
            }

            $trackProgress = array(
                'progressdetail' => $_progressDetail
            );

            $status = Mage::getModel('shipping/tracking_result_status');

            $status->setCarrier($this->_code);
            $status->setCarrierTitle($this->getConfigData('title'));
            $status->setTracking($tracking);
            $status->addData($trackProgress);
            $status->setPopup(1);
            $status->setUrl("http://www.jadlog.com.br/tracking.jsp");
            // $status->setUrl('http://google.com.br');

            return $status;
        } else {
            $error = Mage::getModel('shipping/tracking_result_error');

            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setTracking($tracking);

            return $error;
        }
    }

    private function _getArrayDeliveryDateTime($data)
    {
        $brLocale = new Zend_Locale('pt_BR');

        list($_deliveryDate, $_deliveryTime) = explode(' ', $data);

        $objDeliveryDate = new Zend_Date($_deliveryDate, 'dd/MM/YYYY', $brLocale);
        $_deliveryDate = $objDeliveryDate->toString('YYYY-MM-dd');

        return array($_deliveryDate, $_deliveryTime);
    }

    /**
     * Get Tracking
     *
     * @param array $trackings
     * @return Mage_Shipping_Model_Tracking_Result
     */
    public function getTracking($trackings)
    {
        $result = Mage::getModel('shipping/tracking_result');

        foreach ((array) $trackings as $code) {
            $result->append($this->_getTracking($code));
        }
        return $result;
    }

    /**
     * Protected Get Tracking, opens the request to Correios
     *
     * @param string $code
     * @return boolean
     */
    protected function _getTracking($code)
    {
        var_dump($code);
        return $code;

        $error = Mage::getModel('shipping/tracking_result_error');
        $error->setTracking($code);
        $error->setCarrier($this->_code);
        $error->setCarrierTitle($this->getConfigData('title'));
        $error->setErrorMessage($this->getConfigData('urlerror'));

        $url = 'http://websro.correios.com.br/sro_bin/txect01$.QueryList';
        $url .= '?P_LINGUA=001&P_TIPO=001&P_COD_UNI=' . $code;
        try {
            $client = new Zend_Http_Client();
            $client->setUri($url);
            $content = $client->request();
            $body = $content->getBody();
        } catch (Exception $e) {
            $this->_result->append($error);
            return false;
        }

        if (!preg_match('#<table ([^>]+)>(.*?)</table>#is', $body, $matches)) {
            $this->_result->append($error);
            return false;
        }
        $table = $matches[2];

        if (!preg_match_all('/<tr>(.*)<\/tr>/i', $table, $columns, PREG_SET_ORDER)) {
            $this->_result->append($error);
            return false;
        }

        $progress = array();
        for ($i = 0; $i < count($columns); $i++) {
            $column = $columns[$i][1];

            $description = '';
            $found = false;
            if (preg_match('/<td rowspan="?2"?/i', $column) && preg_match('/<td rowspan="?2"?>(.*)<\/td><td>(.*)<\/td><td><font color="[A-Z0-9]{6}">(.*)<\/font><\/td>/i', $column, $matches)) {
                if (preg_match('/<td colspan="?2"?>(.*)<\/td>/i', $columns[$i+1][1], $matchesDescription)) {
                    $description = str_replace('  ', '', $matchesDescription[1]);
                }

                $found = true;
            } elseif (preg_match('/<td rowspan="?1"?>(.*)<\/td><td>(.*)<\/td><td><font color="[A-Z0-9]{6}">(.*)<\/font><\/td>/i', $column, $matches)) {
                $found = true;
            }

            if ($found) {
                $datetime = explode(' ', $matches[1]);
                $locale = new Zend_Locale('pt_BR');
                $date='';
                $date = new Zend_Date($datetime[0], 'dd/MM/YYYY', $locale);

                $track = array(
                            'deliverydate' => $date->toString('YYYY-MM-dd'),
                            'deliverytime' => $datetime[1] . ':00',
                            'deliverylocation' => htmlentities($matches[2]),
                            'status' => htmlentities($matches[3]),
                            'activity' => htmlentities($matches[3])
                            );

                if ($description !== '') {
                    $track['activity'] = $matches[3] . ' - ' . htmlentities($description);
                }

                $progress[] = $track;
            }
        }

        if (!empty($progress)) {
            $track = $progress[0];
            $track['progressdetail'] = $progress;

            $tracking = Mage::getModel('shipping/tracking_result_status');
            $tracking->setTracking($code);
            $tracking->setCarrier('correios');
            $tracking->setCarrierTitle($this->getConfigData('title'));
            $tracking->addData($track);

            $this->_result->append($tracking);
            return true;
        } else {
            $this->_result->append($error);
            return false;
        }
    }

    /**
     * @method getAllowedMethods
     * @return array
     */
    public function getAllowedMethods()
    {
        return array( $this->_code => $this->getConfigData('title') );
    }

    /* @method collectRates
     * @param  request - Mage_Shipping_Model_Rate_Request
     * @return result
     * Description: Used by Magento itself to define shpping methods & fees
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')) {
            Mage::log('[MAGENTO|JADLOG] Jadlog method is not active');
            return false;
        }

        //Zend_Debug::dump( Mage::getSingleton('checkout/session')->getQuote()->getCouponCode() );
        $couponCode = Mage::getSingleton('checkout/session')->getQuote()->getCouponCode();

        /*if( !empty( $couponCode ) ){
            $coupon = Mage::getModel('salesrule/coupon')->load( $couponCode, 'code' );
            $rule = Mage::getModel('salesrule/rule')->load($coupon->getRuleId());

            $this->_freeShipping = $rule->getSimpleFreeShipping();
            $this->_freeShippingMethod = Mage::getStoreConfig('carriers/'.$this->_code.'/free_method');
        }*/


        // Set variables by request parameter
        $this->_items 		= $request->getAllItems();
        $this->_destZipCode = $request->getDestPostcode();

        $this->_result = Mage::getModel('shipping/rate_result');

        $model = Mage::getModel('jadlogmethod/cep');
        $delay = $model->loadByCep($this->getDestinationZipCode());
        Mage::log('[MAGENTO|JADLOG] Delivery postcode is: '.$this->getDestinationZipCode());

        $modalidades = explode(',', Mage::getStoreConfig('carriers/'.$this->_code.'/modalidade'));
        $modalidadesSource = $this->getModalidades();

        $title = Mage::getStoreConfig('carriers/'.$this->_code.'/title');

        // $extraDelay = $this->getExtraDelay();
        // $extraDelay = 6;
        $extraDelay = Mage::getStoreConfig('carriers/'.$this->_code.'/extra_delay');

        $i = 1;

        $_zip = $this->getDestinationZipCode();
        $_zip = str_replace(array('-', '.'), '', trim($_zip));

        if ($_zip >= 20000000 && $_zip <= 28999999) {
            // add 5 days to rio de janeiro
            $additionalByState = 5;
        } elseif ($_zip >= 29000000 && $_zip <= 29999999) {
            // add 5 days to espirito santo
            $additionalByState = 5;
        } else {
            $additionalByState = 0;
        }

        foreach ($modalidades as $modalidade) {

            // Pre�o
            if ($this->_freeShipping && !empty($this->_freeShippingMethod)) {
                $preco = $this->_freeShippingMethod == $modalidade ? 0 : $this->calcularFrete($modalidade);
            } else {
                $preco = $this->calcularFrete($modalidade);

                $additionalFee = $this->getConfigData('valor_adicional');
                $additionalFeePercentage = 1;
                
                if (!empty($additionalFee)) {
                    $additionalFeePercentage = (((float) str_replace(['%', ','], ['', '.'], $additionalFee))/100) + 1;
                }

                $preco = $preco * $additionalFeePercentage;
            }

            // Avoid empty and/or null values
            if ((float) $preco == 0 && $this->_freeShippingMethod != $modalidade) {
                continue;
            } elseif ((float) $preco <= 0 && $this->_freeShippingMethod != $modalidade) {
                continue;
            }
            // if( empty($preco) || $preco <= 0 ) continue;

            // Nome do metodo
            $methodTitle = Mage::helper('jadlogmethod')->__($modalidadesSource[$modalidade]->alias);

            $column = 'get'.ucfirst($modalidadesSource[$modalidade]->column);

            // Comum
            $tempo = ' ('.($delay->$column() + $extraDelay+$additionalByState).' dias úteis) ';
            // if( !$extraDelay ) $tempo = ' ('.($delay->$column() + $extraDelay).' dias) ';
            // 3 a 5 dias
            // elseif( isset($extraDelay['time']) && $extraDelay['id'] == 8 ) $tempo = ' ('.($delay->$column()+3).' a '.($delay->$column()+5).' '.($extraDelay['time']).') ';
            // 10 a 15 dias
            // elseif( isset($extraDelay['time']) && $extraDelay['id'] == 122 ) $tempo = ' ('.($delay->$column()+10).' a '.($delay->$column()+15).' '.($extraDelay['time']).') ';
            // Sob consulta
            // elseif( isset($extraDelay['time']) ) $tempo = ' ('.($extraDelay['time']).') ';
            // Entrega Imediata
            // else $tempo = ' ('.($delay->$column() + $extraDelay['add']).' dias) ';
            // else $tempo = ' ('.($delay->$column() + $extraDelay).' dias) ';

            $methodTitle .= $tempo;

            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($title);
            $method->setMethod($modalidade);
            $method->setMethodTitle($methodTitle);
            if ($request->getFreeShipping()) {
                $method->setPrice(0);
                $method->setCost(0);
            } else {
                $method->setPrice($preco);
            }

            $this->_result->append($method);
            $i++;
        }

        $this->_updateFreeMethodQuote($request);

        return $this->_result;
    }

    /* @method calcularFrete
     * @return frete
     * Description: Calculetes the shipping fee and returns it as a float number
     */
    public function calcularFrete($modalidade)
    {
        $url = Mage::getStoreConfig('carriers/'.$this->_code.'/webservice_url');
        // $service = 'ValorFreteBean?method=valorar';
        $service = 'ValorFreteBean';
        $method = 'valorar';

        $altura = $this->getAlturas();
        $largura = $this->getLarguras();
        $profundidade = $this->getProfundidades();

        if ($altura == 0) {
            $altura = 12;
        }

        if ($largura == 0) {
            $largura = 20;
        }

        if ($profundidade == 0) {
            $profundidade = 32;
        }

        /* $params = array(
            'method' 		=> $method,
            'vModalidade' 	=> ltrim($modalidade, 'jadlogmethod'),
            'Password' 	  	=> Mage::getStoreConfig('carriers/'.$this->_code.'/senha_acesso'),
            'vSeguro' 	  	=> Mage::getStoreConfig('carriers/'.$this->_code.'/seguro'),
            'vVIDec' 	  	=> $this->getTotalPrice(),
            'vVIColeta'   	=> Mage::getStoreConfig('carriers/'.$this->_code.'/valor_coleta'),
            'vCepOrig'    	=> str_replace( '-', '', Mage::getStoreConfig('shipping/origin/postcode')),
            'vCepDest'    	=> str_replace( '-', '', $this->getDestinationZipCode() ),
            'vPeso' 	  	=> $this->getPesoTotal(),
            // 'vAltura' 	  	=> $altura,
            'vAltura' 	  	=> 0,
            // 'vLargura' 	  	=> $largura,
            'vLargura' 	  	=> 0,
            // 'vProfundidade' => $profundidade,
            'vProfundidade' => 0,
            'vFrap' 		=> 'N',
            'vEntrega' 		=> 'D',
            'vCnpj' 		=> Mage::getStoreConfig('carriers/'.$this->_code.'/cnpj_empresa')
        ); */

        // New REST API
        $params = [
            'frete' => [
                [
                    'cepori' => str_replace('-', '', Mage::getStoreConfig('shipping/origin/postcode')),
                    'cepdes' => str_replace('-', '', $this->getDestinationZipCode()),
                    'frap' => 'N',
                    'peso' => $this->getPesoTotal(),
                    'cnpj' => Mage::getStoreConfig('carriers/'.$this->_code.'/cnpj_empresa'),
                    // 'conta' => 0,
                    // 'contrato' => '10344-6',
                    'conta' => null,
                    'contrato' => null,
                    'modalidade' => ltrim($modalidade, 'jadlogmethod'),
                    'tpentrega' => 'D',
                    'tpseguro' => Mage::getStoreConfig('carriers/'.$this->_code.'/seguro'),
                    'vldeclarado' => $this->getTotalPrice(),
                    'vlcoleta' => Mage::getStoreConfig('carriers/'.$this->_code.'/valor_coleta')
                ]
            ]
        ];
        
        try {
            $curl = curl_init('https://www.jadlog.com.br/embarcador/api/frete/valor');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjU5OTAyfQ.dYxPKmd83yibFFSIUGFHvgrcAkOhnOdB_dcBTWuFz4g']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);

            $content = curl_exec($curl);
            $response = json_decode($content);

            return $response->frete[0]->vltotal;
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }

        // Mage::log('[MAGENTO|JADLOG] The following parameters will be send: '.json_encode($params));

        // $webservice = $this->getWebserviceUrl($service, $params);

        /* try{
            $client = new Zend_Http_Client($url.'/'.$service);
            $client->setConfig(array( 'timeout' => 20 ));

            foreach( $params as $key => $value ) $client->setParameterGet($key, $value);

            libxml_use_internal_errors(true);
            $content = $client->request()->getBody();

            // $file = fopen( '/var/www/html/loja2013/var/report/xmlteste', 'w+' );
            // fwrite( $file, html_entity_decode($content) );
            // fclose( $file );

            $result = new SimpleXMLElement($content, NULL, NULL, 'http://schemas.xmlsoap.org/soap/envelope/');
            $namespaces = $result->getNameSpaces(true);
            $result->registerXPathNamespace('soapenv', 'http://schemas.xmlsoap.org/soap/envelope/');
            $result = $result->xpath('//soapenv:Body');

            try{
                // if( $_SERVER['REMOTE_ADDR'] == '201.6.130.202' ){
                // 	Zend_Debug::dump($namespaces['ns1']);
                // 	Zend_Debug::dump($result->Retorno);
                // 	Zend_Debug::dump($result[0]->valorarResponse->children($namespaces['ns1']));
                // }
                if(!empty($result)){
                    $result = new SimpleXMLElement($result[0]->valorarResponse->children($namespaces['ns1'])->valorarReturn);

                    $result = $result->Jadlog_Valor_Frete;
                }else{
                    Mage::log('[MAGENTO|JADLOG] Webservice error: Empty return');
                    return FALSE;
                }
            }catch(Exception $e){
                Mage::log('[MAGENTO|JADLOG] Webservice error: '.json_encode($e->getMessage()));
                return FALSE;
            }

            if( $result->Retorno == -1 ){
                Mage::log('[MAGENTO|JADLOG] Result returned with error -1: '.json_encode($result));
                return FALSE;
            }

            return str_replace(',', '.', $result->Retorno);
        }catch(Exception $e){
            Mage::log('[MAGENTO|JADLOG] Webservice error: '.json_encode($e->getMessage()));
            return FALSE;
        } */
    }

    /**
     * @method _setFreeMethodRequest
     *
     * Generate free shipping for a product
     *
     * @param string $freeMethod
     * @return void
     */
    protected function _setFreeMethodRequest($freeMethod)
    {
        $this->_freeShipping = true;
        $this->_freeShippingMethod = $freeMethod;
    }

    /*
     *
     */
    public function getDestinationZipCode()
    {
        if (!empty($this->_destZipCode)) {
            return $this->_destZipCode;
        }

        $this->_destZipCode = str_replace('-', '', Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getPostcode());

        return $this->_destZipCode;
    }

    /* @method getWebserviceUrl
     * @param  service - Get service/method to consume
     * @param  params  - Line parameters to be send to a service/method
     * @return webserviceUrl - Returns the full URL for the webservice with service/method set along with the parameters
     * Description: This function is used to get the full URL for an webservice
     */
    protected function getWebserviceUrl($service, $params = array())
    {
        if (count($params) <= 0) {
            return false;
        }
        if (empty($service)) {
            return false;
        }

        // URL principal
        $mainUrl = Mage::getStoreConfig('carriers/'.$this->_code.'/webservice_url').'/'.$service;

        // Linha de par�metros
        $paramsLine = '';
        foreach ($params as $key => $value) {
            $paramsLine .= $key.'='.$value.'&';
        }
        $paramsLine = rtrim($paramsLine, '&');

        return $mainUrl.'&'.$paramsLine;
    }

    /* @method getTotalPrice
     * @return totalPrice
     * Description: Get all cart items and sum the prices
     */
    protected function getTotalPrice()
    {
        // Cart items
        $items = $this->getAllCartItems();
        $total = '';

        // Sum
        foreach ($items as $item) {
            $total += $item->getPrice() * $item->getQty();
        }

        return $total;
    }

    protected function getAllCartItems()
    {
        if (!empty($this->_items)) {
            return $this->_items;
        } else {
            $this->_items = Mage::getModel('checkout/cart')->getItems();
            return $this->_items;
        }
    }

    /**
     *
     */
    public function getExtraDelay()
    {
        $items = $this->getAllCartItems();
        $disponibilidade = array(
            '7' => array( 'order' => 0,  'add' => 1, 	'id'   => 7 ),
            '8' => array( 'order' => 10, 'add' => null, 'time' => 'dias &uacute;teis', 'id' => 8 ),
            '9' => array( 'order' => 20, 'add' => null, 'time' => 'O prazo de envio de um ou mais produtos selecionados deve ser consultado antes do fechamento do pedido. Favor contatar a Neosolar atrav&eacute;s do telefone (11) 4328-5113 ou pelo e-mail vendas@neosolar.com.br', 'id' => 9 ),
            '122' => array( 'order' => 30, 'add' => null, 'time' => 'dias &uacute;teis', 'id' => 122 )
        );

        $break = false;

        foreach ($items as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $break = $product->getDisponibilidade() == '9' ? true : false;
            if ($break) {
                $type = $product->getDisponibilidade();
                break;
            }
        }

        if (!$break) {
            foreach ($items as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                $break = $product->getDisponibilidade() == '122' ? true : false;
                if ($break) {
                    $type = $product->getDisponibilidade();
                    break;
                }
            }
        }

        if (!$break) {
            foreach ($items as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                $break = $product->getDisponibilidade() == '8' ? true : false;
                if ($break) {
                    $type = $product->getDisponibilidade();
                    break;
                }
            }
        }

        if (!$break) {
            foreach ($items as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                $break = $product->getDisponibilidade() == '7' ? true : false;
                if ($break) {
                    $type = $product->getDisponibilidade();
                    break;
                }
            }
        }

        if ($break) {
            return $disponibilidade[$type];
        } else {
            return false;
        }
    }

    /* @method getPesoTotal
     * @return peso_total
     * Description: Gets the real total weight for the products to be dispatched
     */
    public function getPesoTotal()
    {
        $peso_total = 0;

        $cartItems = $this->getAllCartItems();

        foreach ($cartItems as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            /*if($product->getWeight() > $this->getCubagem($product)) $peso = $product->getWeight();
            // else $peso = $this->getCubagem($product);
            else $peso = $product->getWeight();*/

            $peso = $product->getWeight();

            $peso_total += $item->qty * $peso;
        }

        return $peso_total;
    }

    /* @method getCubagem
     * @param  produto - Produto a ser calculado
     * @return result
     * Description: Returns cubic value
     */
    public function getCubagem($produto)
    {
        $altura = $produto->getVolumeAltura();
        $largura = $produto->getVolumeLargura();
        $comprimento = $produto->getVolumeComprimento();

        $result = $altura * $largura * $comprimento;
        return $result;
    }

    public function getAlturas()
    {
        $items = $this->getAllCartItems();
        $model = Mage::getModel('catalog/product');

        $total = '';

        foreach ($items as $item) {
            $total += $model->load($item->getProductId())->getVolumeAltura() * $item->getQty();
        }

        return $total;
    }

    public function getLarguras()
    {
        $items = $this->getAllCartItems();
        $model = Mage::getModel('catalog/product');

        $total = '';

        foreach ($items as $item) {
            $total += $model->load($item->getProductId())->getVolumeLargura() * $item->getQty();
        }

        return $total;
    }

    public function getProfundidades()
    {
        $items = $this->getAllCartItems();
        $model = Mage::getModel('catalog/product');

        $total = '';

        foreach ($items as $item) {
            $total += $model->load($item->getProductId())->getVolumeComprimento() * $item->getQty();
        }

        return $total;
    }

    /* @method getModalidades
     * @return this::_modalidades
     * Description: Get all the delivery types and creates an array indexed by its own value
     */
    public function getModalidades()
    {
        if (!empty($this->_modalidades)) {
            return $this->_modalidades;
        }

        $modalidadesS = Mage::getModel('jadlogmethod/source_modalidades')->toOptionArray();
        foreach ($modalidadesS as $modalidade) {
            $modalidades[$modalidade['value']] = (object) array( 'alias' => $modalidade['alias'], 'label' => $modalidade['label'], 'column' => $modalidade['column'] );
        }

        $this->_modalidades = $modalidades;

        return $this->_modalidades;
    }
}
