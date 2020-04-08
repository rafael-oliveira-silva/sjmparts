<?php

class Cielo_Api_Request_CieloRequestException extends \Exception
{

    private $cieloError;

    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getCieloError()
    {
        return $this->cieloError;
    }

    public function setCieloError(Cielo_Api_Request_CieloError $cieloError)
    {
        $this->cieloError = $cieloError;
        return $this;
    }
}