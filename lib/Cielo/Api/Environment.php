<?php

class Cielo_Api_Environment implements Cielo_Environment
{
    private $api;
    private $apiQuery;

    private function __construct($api, $apiQuery)
    {
        $this->api = $api;
        $this->apiQuery = $apiQuery;
    }

    public static function sandbox()
    {
        $api = 'https://apisandbox.cieloecommerce.cielo.com.br/';
        $apiQuery = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/';

        return new Cielo_Api_Environment($api, $apiQuery);
    }

    public static function production()
    {
        $api = 'https://api.cieloecommerce.cielo.com.br/';
        $apiQuery = 'https://apiquery.cieloecommerce.cielo.com.br/';

        return new Cielo_Api_Environment($api, $apiQuery);
    }

    /**
     * Gets the environment's Api URL
     *
     * @return the Api URL
     */
    public function getApiUrl()
    {
        return $this->api;
    }

    /**
     * Gets the environment's Api Query URL
     *
     * @return the Api Query URL
     */
    public function getApiQueryURL()
    {
        return $this->apiQuery;
    }
}