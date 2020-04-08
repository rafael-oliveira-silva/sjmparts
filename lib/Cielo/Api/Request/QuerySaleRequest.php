<?php

use Cielo_Api_Request_AbstractSaleRequest as AbstractSaleRequest;
use Cielo_Environment as Environment;
use Cielo_Merchant as Merchant;
use Cielo_Api_Sale as Sale;

class Cielo_Api_Request_QuerySaleRequest extends AbstractSaleRequest
{

    private $environment;

    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->environment = $environment;
    }

    public function execute($paymentId)
    {
        $url = $this->environment->getApiQueryURL() . '1/sales/' . $paymentId;

        return $this->sendRequest('GET', $url);
    }

    protected function unserialize($json)
    {
        return Sale::fromJson($json);
    }
}