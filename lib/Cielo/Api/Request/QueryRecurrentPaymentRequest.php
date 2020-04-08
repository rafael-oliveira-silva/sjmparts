<?php

use Cielo_Api_Request_AbstractSaleRequest as AbstractSaleRequest;
use Cielo_Environment as Environment;
use Cielo_Merchant as Merchant;
use Cielo_Api_RecurrentPayment as RecurrentPayment;

class Cielo_Api_Request_QueryRecurrentPaymentRequest extends AbstractSaleRequest
{

    private $environment;

    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->environment = $environment;
    }

    public function execute($recurrentPaymentId)
    {
        $url = $this->environment->getApiQueryURL() . '1/RecurrentPayment/' . $recurrentPaymentId;

        return $this->sendRequest('GET', $url);
    }

    protected function unserialize($json)
    {
        return RecurrentPayment::fromJson($json);
    }
}
