<?php
class DDE_Loggi_Model_Carrier_Loggi extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    const GOOGLE_MAPS_API_URI = 'https://maps.googleapis.com/maps/api/geocode/json';
    const LOGGI_API_URI = 'https://staging.loggi.com/graphql';
    const LOGGI_STATUS_FINISHED = 'Entregue';
    const LOGGI_STATUS_IN_PROGRESS = 'Em processo de entrega';

    protected $apiUser;
    protected $apiToken;
    protected $_code = 'loggi';
    protected $destinationPoints;
    protected $fromZip;
    protected $method;
    protected $packageType;
    protected $packageValue;
    protected $result;
    protected $transportType;
    protected $toZip;
    protected $url;
    protected $withdrawalPoints;

    /**
     * Collect rates for this shipping method based on information in $request
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if ($this->initialCheck($request) === false) {
            return false;
        }

        $result = Mage::getModel('shipping/rate_result');

        $this->setPackageType();
        $this->setTransportType();
        $this->setWayPoints();

        $showShippingMethod = false;
        $toZip = (int) $this->toZip;
        if (($toZip >= 1000000 && $toZip <= 5999999) || ($toZip >= 8000000 && $toZip <= 8499999)) {
            $showShippingMethod = true;
        }

        if (!$showShippingMethod) {
            return false;
        }

        $data = $this->getLoggiEstimateOrder();

        foreach ($data as $methodCode => $methodResponse) {
            if( empty($methodResponse) ) continue;

            $methodPrice = (object) $methodResponse['prices'][0];
            
            // if (!isset($methodPrice->estimatedCost) || empty($methodPrice->estimatedCost) || $methodPrice->estimatedCost <= 0) {
            //     continue;
            // }

            // $methodName = ucfirst($methodCode) .' - ' .preg_replace('(\<br\>)', ' ', $methodPrice->label);
            $methodName = ucfirst($methodCode);

            $additionalFee = $this->getConfigData('additional_fee_percentage');
            $additionalFeePercentage = 1;
            
            if (!empty($additionalFee)) {
                $additionalFeePercentage = (((float) str_replace(['%', ','], ['', '.'], $additionalFee))/100) + 1;
            }

            $additionalFeeFixed = $this->getConfigData('additional_fee');
            $additionalFeeFixedAmount = 0;
            
            if (!empty($additionalFeeFixed)) {
                $additionalFeeFixedAmount = (float) $additionalFeeFixed;
            }
            
            $price = ($methodPrice->estimatedCost * $additionalFeePercentage) + $additionalFeeFixedAmount;

            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod($this->_code.'_'.$methodCode);
            $method->setMethodTitle($methodName);
            $method->setPrice($price);
            $method->setCost($price);
            $result->append($method);
        }
        
        return $result;
    }

    private function initialCheck(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('enabled')) {
            return false;
        }

        $origCountry = Mage::getStoreConfig(
            'shipping/origin/country_id',
            $this->getStore()
        );
        $destCountry = $request->getDestCountryId();

        if ($origCountry != 'BR' || $destCountry != 'BR') {
            return false;
        }

        $this->fromZip = Mage::getStoreConfig(
            'shipping/origin/postcode',
            $this->getStore()
        );
        $this->toZip = $request->getDestPostcode();

        // Fix ZIP code
        $this->fromZip = str_replace(
            array('-', '.'),
            '',
            trim($this->fromZip)
        );
        $this->toZip = str_replace(array('-', '.'), '', trim($this->toZip));

        if (!preg_match('/^([0-9]{8})$/', $this->fromZip)) {
            return false;
        }

        if (!trim($this->toZip)) {
            return false;
        }

        $this->packageValue = $request
            ->getBaseCurrency()
            ->convert(
                $request->getPackageValue(),
                $request->getPackageCurrency()
            );

        if (
            $this->packageValue < $this->getConfigData('min_order_value') ||
            $this->packageValue > $this->getConfigData('max_order_value')
        ) {
            $this->setError(
                'Valor da compra fora dos limites para o uso deste serviço'
            );
            return $this->result;
        }

        if (!preg_match('/^([0-9]{8})$/', $this->toZip)) {
            $this->setError('CEP inválido');
            return $this->result;
        }

        $this->apiToken = $this->getConfigData('api_token');
        $this->apiUser = $this->getConfigData('api_user');
    }

    public function getAllowedMethods()
    {
        return array($this->code => $this->getConfigData('name'));
    }

    private function getLoggiEstimateOrder()
    {
        $response = $this->getLoggiResponse($this->getQueryString());

        $data = json_decode($response, true);
        $estimatedOrder = (object) $data['data'];

        return $estimatedOrder;
    }

    private function getQueryString()
    {
        $queryString = [];
        $transportTypes = explode(',', $this->transportType);

        foreach ($transportTypes as $transportType) {
            $transportType = trim($transportType);

            $queryString[] = $transportType.': estimateOrder(
                city: 1
                transportType: '.$transportType.'
                points: [
                    {
                        lat: '.$this->withdrawalPoints->lat.',
                        lng: '.$this->withdrawalPoints->lng.'
                    }
                    {
                        lat: '.$this->destinationPoints->lat.',
                        lng: '.$this->destinationPoints->lng.'
                    }
                ]
                optimize: true
            ) {
                prices {
                    label
                    estimatedCost
                }
            }';
        }

        $queryString = '{'.implode(',', $queryString).'}';

        return preg_replace('/\n\t/', '', $queryString);
    }

    private function getLoggiResponse($query)
    {
        $query = (object) ['query' => $query];
        $token = $this->apiUser . ':' . $this->apiToken;
        try {
            $client = new Zend_Http_Client(self::LOGGI_API_URI);
            $client->setHeaders('Authorization', 'ApiKey ' . $token);
            $client->setHeaders('Content-Type', 'application/json');
            $client->setRawData(json_encode($query, true), 'application/json');

            return $client->request(Zend_Http_Client::POST)->getBody();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getMapsResponse($address)
    {
        try {
            $client = new Zend_Http_Client(self::GOOGLE_MAPS_API_URI);
            $client->setParameterGet('address', $address);
            $client->setParameterGet(
                'key',
                $this->getConfigData('google_maps_api_key')
            );
            $client->setParameterGet(
                'region',
                'br'
            );

            return $client->request(Zend_Http_Client::GET)->getBody();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function setWayPoints()
    {
        $withdrawalPointsResponse = $this->getMapsResponse($this->fromZip);
        $destinationPointsResponse = $this->getMapsResponse($this->toZip);
        
        $withdrawalPoints = json_decode($withdrawalPointsResponse);
        $destinationPoints = json_decode($destinationPointsResponse);

        $this->withdrawalPoints = $withdrawalPoints->results[0]->geometry
            ->location;
        $this->destinationPoints = $destinationPoints->results[0]->geometry
            ->location;
    }

    private function setPackageType()
    {
        $availablePackagesType = array_map(function ($item) {
            return $item['value'];
        }, DDE_Loggi_Options_PackageTypeList::getAllOptions());

        $packageType = $this->getConfigData('package_type');

        if (in_array($packageType, $availablePackagesType)) {
            $this->packageType = $packageType;
        }
    }

    private function setTransportType()
    {
        $availableTransportType = array_map(function ($item) {
            return $item['value'];
        }, DDE_Loggi_Options_TransportTypeList::getAllOptions());

        $transportType = $this->getConfigData('transport_type');

        $this->transportType = $transportType;
    }

    private function setError($message)
    {
        $result = Mage::getModel('shipping/rate_result');

        $error = Mage::getModel('shipping/rate_result_error');
        $error->setCarrier($this->code);
        $error->setCarrierTitle($this->getConfigData('title'));
        $error->setErrorMessage($message);

        $result->append($error);

        return $result;
    }

    public function isTrackingAvailable()
    {
        return true;
    }

    public function getTrackingInfo($tracking)
    {
        $result = $this->getTracking($tracking);

        if ($result instanceof Mage_Shipping_Model_Tracking_Result) {
            if ($trackings = $result->getAllTrackings()) {
                return $trackings[0];
            }
        } elseif (is_string($result) && !empty($result)) {
            return $result;
        }

        return false;
    }

    public function getTracking($trackings)
    {
        $result = Mage::getModel('shipping/tracking_result');
        foreach ((array) $trackings as $code) {
            $entry = $this->getTrack($code);
            $result->append($entry);
        }

        return $result;
    }

    private function getSearchOrderQuery($code)
    {
        $query = "{
                          order(id: {$code}) {
                            status
                            customer {
                              fullName
                            }
                            orderReceipt
                            created
                            started
                            finished
                            waypoints {
                              addressDisplay
                              waitTime
                              overTime
                              instructions
                              checkin
                              checkout
                            }
                            driver {
                              fullName
                              mobile1
                            }
                          }
                        }";

        return preg_replace('/\n\t/', ' ', $query);
    }

    protected function getTrackingRequest($query)
    {
        $query = (object) ['query' => $query];
        $token = $this->apiUser . ':' . $this->apiToken;

        try {
            $client = new Zend_Http_Client(
                DDE_Loggi_Model_Carrier_Loggi::LOGGI_API_URI
            );
            $client->setHeaders('Authorization', 'ApiKey ' . $token);
            $client->setHeaders('Content-Type', 'application/json');
            $client->setRawData(json_encode($query, true), 'application/json');

            return $client->request(Zend_Http_Client::POST)->getBody();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getTrackingProgress($data)
    {
        $trackProgress = [];

        $trackSummary = $data['waypoints'];

        foreach ($trackSummary as $track) {
            $date = new Zend_Date(
                $track['checkout'],
                Zend_Date::TIMESTAMP,
                new Zend_Locale('pt_BR')
            );

            $progress = [
                'deliverydate' => $date->toString('YYYY-MM-dd'),
                'deliverytime' => $date->toString('hh:mm:ss'),
                'activity' => $track['instructions'],
                'deliverylocation' => $track['addressDisplay']
            ];

            array_push($trackProgress, $progress);
        }

        $date = new Zend_Date(
            $data['finished'],
            Zend_Date::TIMESTAMP,
            new Zend_Locale('pt_BR')
        );

        $finalTrack = array(
            'deliverydate' => $date->toString('YYYY-MM-dd'),
            'deliverytime' => $date->toString('hh:mm:ss'),
            'status' => $this->filterDeliveryStatus($data['status'])
        );

        array_push($trackProgress, $finalTrack);

        return $trackProgress;
    }

    protected function getTrack($code)
    {
        $result = Mage::getModel('shipping/rate_result');

        $error = Mage::getModel('shipping/tracking_result_error');
        $error->setTracking($code);
        $error->setCarrier($this->code);
        $error->setCarrierTitle($this->getConfigData('title'));
        $error->setErrorMessage($this->getConfigData('urlerror'));

        $request = $this->getTrackingRequest(
            $this->getSearchOrderQuery($code)
        );
        $data = json_decode($request, true);
        $data = $data['data']['order'];

        $progress = !empty($data) ? $this->getTrackingProgress($data) : null;

        if (!empty($progress)) {
            $track = array_pop($progress);
            $track['progressdetail'] = $progress;

            $tracking = Mage::getModel('shipping/tracking_result_status');
            $tracking->setTracking($code);
            $tracking->setCarrier($this->code);
            $tracking->setCarrierTitle($this->getConfigData('title'));
            $tracking->addData($track);

            return $tracking;
        } else {
            return $error;
        }
    }

    private function filterDeliveryStatus($status)
    {
        $status = strtolower($status);

        switch ($status) {
            case 'finished':
                return self::LOGGI_STATUS_FINISHED;
            case 'in_progress':
                return self::LOGGI_STATUS_IN_PROGRESS;
            default:
                return 'Status de entrega desconhecido';
        }
    }
}
