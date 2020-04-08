<?php

use Cielo_Api_Request_AbstractSaleRequest as AbstractSaleRequest;
use Cielo_Environment as Environment;
use Cielo_Merchant as Merchant;
use Cielo_Api_Payment as Payment;

class Cielo_Api_Request_UpdateSaleRequest extends AbstractSaleRequest
{

    private $environment;

    private $type;

    private $serviceTaxAmount;

    private $amount;

    public function __construct($type, Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->environment = $environment;
        $this->type = $type;
    }

    public function execute($paymentId)
    {
        $url = $this->environment->getApiUrl() . '1/sales/' . $paymentId . '/' . $this->type;
        $params = [];

        if ($this->amount != null) {
            $params['amount'] = $this->amount;
        }

        if ($this->serviceTaxAmount != null) {
            $params['serviceTaxAmount'] = $this->serviceTaxAmount;
        }

        $url .= '?' . http_build_query($params);

        return $this->sendRequest('PUT', $url);
    }

    protected function unserialize($json)
    {
        return Payment::fromJson($json);
    }

    public function getServiceTaxAmount()
    {
        return $this->serviceTaxAmount;
    }

    public function setServiceTaxAmount($serviceTaxAmount)
    {
        $this->serviceTaxAmount = $serviceTaxAmount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
}