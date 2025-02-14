<?php
/**
 * @project     SocialLogin
 * @package     LitExtension_SocialLogin
 * @author      LitExtension
 * @email       litextension@gmail.com
 */

class LitExtension_SocialLogin_Model_Twitter_Client
{
    const REDIRECT_URI_ROUTE = 'le_sociallogin/twitter/connect';
    const REQUEST_TOKEN_URI_ROUTE = 'le_sociallogin/twitter/request';

    const OAUTH_URI = 'https://api.twitter.com/oauth';
    const OAUTH2_SERVICE_URI = 'https://api.twitter.com/1.1';    

    const XML_PATH_ENABLED = 'le_sociallogin/twitter/enabled';
    const XML_PATH_CLIENT_ID = 'le_sociallogin/twitter/api_key';
    const XML_PATH_CLIENT_SECRET = 'le_sociallogin/twitter/secret';

    protected $clientId = null;
    protected $clientSecret = null;
    protected $redirectUri = null;
    protected $client = null;
    protected $token = null;
    protected $protocol = "http";

    public function __construct()
     {
         if(($this->isEnabled = $this->_isEnabled())) {
             $this->clientId = $this->_getClientId();
             $this->clientSecret = $this->_getClientSecret();

             $isSecure = Mage::app()->getStore()->isCurrentlySecure();
             if($isSecure){
                 $this->protocol = "https";
             }

             $this->redirectUri = Mage::getModel('core/url')->sessionUrlVar(
                 Mage::getUrl(self::REDIRECT_URI_ROUTE, array('_secure'=>true))
             );

            $this->client = new Zend_Oauth_Consumer(
                array(
                    'callbackUrl' => $this->redirectUri,
                    'siteUrl' => self::OAUTH_URI,
                    'authorizeUrl' => self::OAUTH_URI.'/authenticate',
                    'consumerKey' => $this->clientId,
                    'consumerSecret' => $this->clientSecret
                )
            );
         }
    }

    public function isEnabled()
    {
        return (bool) $this->isEnabled;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    public function setAccessToken($token)
    {
        $this->token = unserialize($token);
    }

    public function getAccessToken()
    {
        if(empty($this->token)) {
            $this->fetchAccessToken();
        }

        return serialize($this->token);
    }

    public function createAuthUrl()
    {
        return Mage::getUrl('le_sociallogin/twitter/request', array("mainw_protocol" => $this->protocol));
    }

    public function fetchRequestToken()
    {
        try{
            $requestToken = $this->client->getRequestToken();
            if($requestToken == FALSE){
                echo "Connect to https://api.twitter.com is false ! <br />Error code :".$this->client->getLastResponse() ;
                return ;
            }
        }catch (Zend_Oauth_Exception $e){
            echo 'Connect to https://api.twitter.com is false ! <br />Error code : '.$e->getMessage();
            return ;
        }

        Mage::getSingleton('core/session')
            ->setTwitterRequestToken(serialize($requestToken));

        $this->client->redirect();
    }

    protected function fetchAccessToken()
    {
        if (!($params = Mage::app()->getFrontController()->getRequest()->getParams())
            ||
            !($requestToken = Mage::getSingleton('core/session')
                ->getTwitterRequestToken())
            ) {
            throw new Exception(
                Mage::helper('le_sociallogin')
                    ->__('Unable to retrieve access code.')
            );
        }

        if(!($token = $this->client->getAccessToken(
                    $params,
                    unserialize($requestToken)
                )
            )
        ) {
            throw new Exception(
                Mage::helper('le_sociallogin')
                    ->__('Unable to retrieve access token.')
            );
        }

        Mage::getSingleton('core/session')->unsTwitterRequestToken();

        return $this->token = $token;
    }

    public function api($endpoint, $method = 'GET', $params = array())
    {
        if(empty($this->token)) {
            throw new Exception(
                Mage::helper('le_sociallogin')
                    ->__('Unable to proceeed without an access token.')
            );
        }

        $url = self::OAUTH2_SERVICE_URI.$endpoint; 
        
        $response = $this->_httpRequest($url, strtoupper($method), $params);

        return $response;
    }

    protected function _httpRequest($url, $method = 'GET', $params = array())
    {
        $client = $this->token->getHttpClient(
            array(
                'callbackUrl' => $this->redirectUri,
                'siteUrl' => self::OAUTH_URI,
                'consumerKey' => $this->clientId,
                'consumerSecret' => $this->clientSecret
            )
        );

        $client->setUri($url);
        
        switch ($method) {
            case 'GET':
                $client->setMethod(Zend_Http_Client::GET);
                $client->setParameterGet($params);
                break;
            case 'POST':
                $client->setMethod(Zend_Http_Client::POST);
                $client->setParameterPost($params);
                break;
            case 'DELETE':
                $client->setMethod(Zend_Http_Client::DELETE);
                break;
            default:
                throw new Exception(
                    Mage::helper('le_sociallogin')
                        ->__('Required HTTP method is not supported.')
                );
        }

        $response = $client->request();

        Mage::log($response->getStatus().' - '. $response->getBody());

        $decoded_response = json_decode($response->getBody());

        if($response->isError()) {
            $status = $response->getStatus();
            if(($status == 400 || $status == 401 || $status == 429)) {
                if(isset($decoded_response->error->message)) {
                    $message = $decoded_response->error->message;
                } else {
                    $message = Mage::helper('le_sociallogin')
                        ->__('Unspecified OAuth error occurred.');
                }

                throw new LitExtension_SocialLogin_TwitterOAuthException($message);
            } else {
                $message = sprintf(
                    Mage::helper('le_sociallogin')
                        ->__('HTTP error %d occurred while issuing request.'),
                    $status
                );

                throw new Exception($message);
            }
        }

        return $decoded_response;
    }

    protected function _isEnabled()
    {
        return $this->_getStoreConfig(self::XML_PATH_ENABLED);
    }

    protected function _getClientId()
    {
        return $this->_getStoreConfig(self::XML_PATH_CLIENT_ID);
    }

    protected function _getClientSecret()
    {
        return $this->_getStoreConfig(self::XML_PATH_CLIENT_SECRET);
    }

    protected function _getStoreConfig($xmlPath)
    {
        return Mage::getStoreConfig($xmlPath, Mage::app()->getStore()->getId());
    }

}

class LitExtension_SocialLogin_TwitterOAuthException extends Exception
{}
