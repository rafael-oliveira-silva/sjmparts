<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Mandaê
 *
 * @category   Mandae
 * @package    Mandae_Shipping
 * @author     Thiago Contardi
 * @copyright  Copyright (c) 2017 Bizcommerce
 * @author     Bruno Ferreira
 * @copyright  Copyright (c) 2019 Mandaê
 */
class Mandae_Shipping_Model_Api
{
    const URL_API = 'https://api.mandae.com.br/v3/';
    const SANDBOX_URL_API = 'https://sandbox.api.mandae.com.br/v3/';

    protected $_helper;

    /**
     * @param $data
     * @param Mandae_Shipping_Model_Carrier_Mandae $carrier
     */
    public function shippingRate($data, Mandae_Shipping_Model_Carrier_Mandae $carrier)
    {
        $result = null;

        $postcode = $data['postcode'];
        $url = $this->_getUrl() . 'postalcodes/' . $postcode . '/rates';

        $args = array(
            'items' => $data['items'],
        );

        $this->_getHelper()->log('E: ' . $url . '. DATA:' . json_encode($args));
        $rate = $this->_request($carrier, $url, 'POST', $args);
        $this->_getHelper()->log('R:' . json_encode($rate));

        return $rate;
    }


    /**
     *
     * @param Mandae_Shipping_Model_Carrier_Mandae $carrier
     * @return mixed
     */
    public function tracking(Mandae_Shipping_Model_Carrier_Mandae $carrier, $trackingCode)
    {
        $result = null;

        $url = $this->_getUrl() . 'trackings/' . $trackingCode;

        $this->_getHelper()->log('TRACKING E: '. $url);
        $tracking = $this->_request($carrier, $url);
        $this->_getHelper()->log('TRACKING R:' . json_encode($tracking));

        if (property_exists($tracking, 'body')) {
            $result = json_decode($tracking->getBody());
        }

        return $result;

    }

    /**
     * @param Mandae_Shipping_Model_Carrier_Mandae $carrier
     * @param $url
     * @param string $method
     * @param null $data
     *
     * @return Zend_Http_Response
     */
    protected function _request(Mandae_Shipping_Model_Carrier_Mandae $carrier, $url, $method = 'GET', $data = null)
    {
        $config = [
            'headers' => [
                'Authorization' => $carrier->getConfigData('token'),
            ],
            'http_errors' => false
        ];

        if ($data) {
            $config['json'] = $data;
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request($method, $url, $config);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $responseBody = $response->getBody()->getContents();

        return json_decode($responseBody);
    }

    /**
     * @return Mandae_Shipping_Helper_Data|Mage_Core_Helper_Abstract
     */
    protected function _getHelper()
    {
        if (!$this->_helper) {
            $this->_helper = Mage::helper('mandae');
        }
        return $this->_helper;
    }

    protected function getStoreId()
    {
        return Mage::app()->getStore()->getStoreId();
    }

    protected function _getUrl()
    {
        return ($this->_getHelper()->isSandbox()) ? self::SANDBOX_URL_API : self::URL_API;
    }
}