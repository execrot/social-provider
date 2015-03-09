<?php

/**
 * The PHP class for vk.com API and to support OAuth.
 *
 * @author Vlad Pronsky <vladkens@yandex.ru>, Edward Komissarov <execrot@gmail.com>
 * @license https://raw.github.com/vladkens/VK/master/LICENSE MIT
 * @version 0.1.6
 */
 
namespace Social\Adapter\Vk\Sdk;

class VK
{
    /**
     * VK application id.
     * @var string
     */
    private $_appId;
    
    /**
     * VK application secret key.
     * @var string
     */
    private $_apiSecret;
    
    /**
     * API version. If null uses latest version.
     * @var int
     */
    private $_apiVersion;
    
    /**
     * VK access token.
     * @var string
     */
    private $_accessToken;
    
    /**
     * Authorization status.
     * @var bool
     */
    private $_auth = false;
    
    /**
     * Instance curl.
     * @var resource
     */
    private $_ch;
    
    const AUTHORIZE_URL        = 'https://oauth.vk.com/authorize';
    const ACCESS_TOKEN_URL     = 'https://oauth.vk.com/access_token';
    const API_URL              = 'https://api.vk.com/method';
    const DEFAULT_CALLBACK_URL = 'https://api.vk.com/blank.html';

    /**
     * @param string $appId
     * @param string $apiSecret
     * @param string|null $accessToken
     *
     * @throws Exception\InvalidToken
     */
    public function __construct($appId, $apiSecret, $accessToken = null)
    {
        $this->_appId       = $appId;
        $this->_apiSecret   = $apiSecret;
        $this->_accessToken = $accessToken;
        
        $this->_ch = curl_init();
        
        if (!is_null($this->_accessToken)) {
            if (!$this->checkAccessToken()) {
                throw new Exception\InvalidToken($this->_accessToken);
            } else {
                $this->_auth = true;
            }
        }
    }
    
    /**
     * Destructor. Closing curl handler.
     *
     * @return  void
     */
    public function __destruct()
    {
        curl_close($this->_ch);
    }
    
    /**
     * Set special API version.
     *
     * @param int $version
     *
     * @return  void
     */
    public function setApiVersion($version)
    {
        $this->_apiVersion = $version;
    }
    
    /**
     * Returns base API url with given format (json by default)
     *
     * @param string $method
     * @param string $responseFormat
     *
     * @return  string
     */
    public function getApiUrl($method, $responseFormat = 'json')
    {
        return self::API_URL . '/' . $method . '.' . $responseFormat;
    }
    
    /**
     * Returns authorization link with passed parameters.
     *
     * @param array  $scope
     * @param string $callbackUrl
     * @param bool   $testMode
     *
     * @return  string
     */
    public function getAuthorizeUrl(
        array $scope = array(),
        $callbackUrl = self::DEFAULT_CALLBACK_URL,
        $testMode = false
    ) {
        $parameters = array(
            'client_id'     => $this->_appId,
            'scope'         => implode(',', $scope),
            'redirect_uri'  => $callbackUrl,
            'response_type' => 'code'
        );
        
        if ($testMode) {
            $parameters['test_mode'] = 1;
        }
            
        return $this->_createUrl(self::AUTHORIZE_URL, $parameters);
    }

    /**
     * Returns access token by code received on authorization link.
     *
     * @param string $code
     * @param string $callbackUrl
     *
     * @return mixed
     *
     * @throws Exception\AlreadyAuthorized
     * @throws Exception\AccessTokenGettingError
     */
    public function getAccessToken($code, $callbackUrl = self::DEFAULT_CALLBACK_URL)
    {
        if (!is_null($this->_accessToken) && $this->_auth) {
            throw new Exception\AlreadyAuthorized();
        }
        
        $parameters = array(
            'client_id'     => $this->_appId,
            'client_secret' => $this->_apiSecret,
            'code'          => $code,
            'redirect_uri'  => $callbackUrl
        );

        $response = json_decode($this->_request($this->_createUrl(
            self::ACCESS_TOKEN_URL,
            $parameters
        )), true);

        if (isset($response['error'])) {

            throw new Exception\AccessTokenGettingError(
                $response['error'],
                isset($response['description'])?$response['description']:null
            );
        }

        $this->_auth = true;
        $this->_accessToken = $response['access_token'];

        return $response;
    }
    
    /**
     * Return user authorization status.
     *
     * @return bool
     */
    public function isAuth()
    {
        return $this->_auth;
    }
    
    /**
     * Check for validity access token.
     *
     * @return bool
     */
    public function checkAccessToken()
    {
        if (is_null($this->_accessToken)) {
            return false;
        }
        
        return !empty($this->api('getUserSettings')['response']);
    }
    
    /**
     * Execute API method with parameters and return result.
     *
     * @param string $method
     * @param array  $parameters
     * @param string $format
     * @param string $requestMethod
     *
     * @return mixed
     */
    public function api($method, $parameters = array(), $format = 'array', $requestMethod = 'get')
    {
        $parameters['timestamp'] = time();
        $parameters['api_id']    = $this->_appId;
        $parameters['random']    = rand(0, 10000);
        
        if (!is_null($this->_accessToken)) {
            $parameters['access_token'] = $this->_accessToken;
        }
        if (!is_null($this->_apiVersion)) {
            $parameters['v'] = $this->_apiVersion;
        }

        ksort($parameters);
        
        $sig = '';
        foreach ($parameters as $key => $value) {
            $sig .= $key . '=' . $value;
        }
        $sig .= $this->_apiSecret;
        
        $parameters['sig'] = md5($sig);
        
        if ($method == 'execute' || $requestMethod == 'post') {

            $response = $this->_request(
                $this->getApiUrl(
                    $method,
                    $format == 'array' ? 'json' : $format
                ),
                "POST",
                $parameters
            );
        }
        else {
            $response = $this->_request(
                $this->_createUrl(
                    $this->getApiUrl(
                        $method,
                        $format == 'array' ? 'json' : $format
                    ),
                    $parameters
                )
            );
        }

        return $format == 'array' ? json_decode($response, true) : $response;
    }
    
    /**
     * Combines keys and values to url format and return url.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return  string
     */
    private function _createUrl($url, $parameters)
    {
        $url .= '?' . http_build_query($parameters);
        return $url;
    }
    
    /**
     * Executes request on link.
     *
     * @param string $url
     * @param string $method
     * @param array  $postFields
     *
     * @return  string
     */
    private function _request($url, $method = 'GET', $postFields = array())
    {
        curl_setopt_array($this->_ch, array(
            CURLOPT_USERAGENT       => 'VK/1.0 (+https://github.com/vladkens/VK))',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_POST            => ($method == 'POST'),
            CURLOPT_POSTFIELDS      => $postFields,
            CURLOPT_URL             => $url
        ));
        
        return curl_exec($this->_ch);
    }
}
    
