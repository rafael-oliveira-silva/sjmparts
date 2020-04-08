<?php

namespace Rede\Service;

use Rede\eRede;
use Rede\Store;
use Rede\Transaction;
use RuntimeException;

abstract class AbstractService
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';

    /**
     * @var resource
     */
    protected $curl;

    /**
     * @var Store
     */
    protected $store;

    /**
     * AbstractService constructor.
     *
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return Transaction
     * @throws \InvalidArgumentException, \RuntimeException, RedeException
     */
    abstract public function execute();

    /**
     * @return string Gets the service that will be used on the request
     */
    abstract protected function getService();

    /**
     * @param string $response Parses the HTTP response from Rede
     * @param string $statusCode The HTTP status code
     *
     * @return mixed
     */
    abstract protected function parseResponse($response, $statusCode);

    /**
     * @param string $body
     * @param string $method
     *
     * @return mixed
     * @throws \RuntimeException
     */
    protected function sendRequest($body = null, $method = 'GET')
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }

        $headers = [
            'User-Agent: ' . eRede::USER_AGENT . ' ' . php_uname(),
            'Accept: application/json'
        ];

        $this->curl = curl_init($this->store->getEnvironment()->getEndpoint($this->getService()));

        curl_setopt(
            $this->curl,
            CURLOPT_USERPWD,
            sprintf('%s:%s', $this->store->getFiliation(), $this->store->getToken())
        );

        if (!defined('CURL_SSLVERSION_TLSv1_2')) {
            define('CURL_SSLVERSION_TLSv1_2', 6);

            error_log('Rede: Atenção, por motivos de segurança, recomendamos fortemente que você atualize a versão do seu PHP.');
        }

        curl_setopt($this->curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, true);

        switch ($method) {
        case 'GET':
            break;
            case 'POST':
                curl_setopt($this->curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        if ($body !== null) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);

            $headers[] = 'Content-Type: application/json; charset=utf8';
        } else {
            $headers[] = 'Content-Length: 0';
        }

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($this->curl);
        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        \Mage::log(
            trim(
                sprintf("Request Rede\n%s %s\n%s\n\n%s",
                    $method,
                    $this->store->getEnvironment()->getEndpoint($this->getService()),
                    implode("\n", $headers),
                    preg_replace('/"(cardnumber|securitycode)":"[^"]+"/i', '"\1":"***"', $body)
                )
            )
        );

        \Mage::log(
            sprintf("Response Rede\nStatus Code: %s\n\n%s",
                $statusCode,
                $response
            )
        );

        if (curl_errno($this->curl)) {
            throw new RuntimeException('Curl error: ' . curl_error($this->curl));
        }

        curl_close($this->curl);

        $this->curl = null;

        return $this->parseResponse($response, $statusCode);
    }
}
