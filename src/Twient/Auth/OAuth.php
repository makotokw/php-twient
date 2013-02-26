<?php
/**
 * Twient\Auth\OAuth class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Twient\Auth;

class OAuth implements AuthInterface
{
    protected $requestTokenUrl = 'https://api.twitter.com/oauth/request_token';
    protected $authorizeUrl = 'https://api.twitter.com/oauth/authorize';
    protected $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';

    protected $signatureMethod = null;
    protected $consumer = null;
    protected $token = null;

    public function __construct($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
    {
        $this->signatureMethod = new \Twient\Auth\OAuth\HMACSHA1SignatureMethod();
        $this->consumer = new \Twient\Auth\OAuth\Consumer($consumerKey, $consumerSecret);
        if (!empty($oauthToken) && !empty($oauthTokenSecret)) {
            $this->token = new \Twient\Auth\OAuth\Consumer($oauthToken, $oauthTokenSecret);
        }
    }

    public function getRequestToken($callback = null)
    {
        $params = array();
        if (!empty($callback)) {
            $params['oauth_callback'] = $callback;
        }
        $response = $this->doRequest($this->requestTokenUrl, 'GET', $params);
        $token = \Twient\Auth\OAuth\Util::parseParameters($response);
        $this->token = new \Twient\Auth\OAuth\Consumer($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    public function getAuthorizeUrl($token)
    {
        if (is_array($token)) {
            $token = $token['oauth_token'];
        }
        return $this->authorizeUrl . '?oauth_token=' . $token;
    }

    public function getAccessToken($verifier = false)
    {
        $params = array();
        if (!empty($verifier)) {
            $params['oauth_verifier'] = $verifier;
        }
        $response = $this->doRequest($this->accessTokenUrl, 'GET', $params);
        $token = \Twient\Auth\OAuth\Util::parseParameters($response);
        $this->token = new \Twient\Auth\OAuth\Consumer($token['oauth_token'], $token['oauth_token_secret']);
        return $token;
    }

    public function sign(array $data)
    {
        $signedData = $data;
        $signedData['headers'] = array();

        $req = \Twient\Auth\OAuth\Request::fromConsumerAndToken(
            $this->consumer,
            $this->token,
            strtoupper($data['method']),
            $data['url'],
            $data['params']
        );
        $req->signRequest($this->signatureMethod, $this->consumer, $this->token);

        $method = strtolower($data['method']);
        switch ($method) {
            case 'get':
                $signedData['url'] = $req->toUrl();
                $signedData['params'] = array();
                break;
            default:
                $signedData['url'] = $req->getNormalizedHttpUrl();
                $signedData['post_data'] = $req->toPostData();
                break;
        }
        return $signedData;
    }

    private function doRequest($url, $method, $params)
    {
        $request = \Twient\Auth\OAuth\Request::fromConsumerAndToken(
            $this->consumer,
            $this->token,
            $method,
            $url,
            $params
        );
        $request->signRequest($this->signatureMethod, $this->consumer, $this->token);

        $http = new \Twient\Request\BaseRequest();
        $method = strtolower($method);
        switch ($method) {
            case 'get':
                return $http->get($request->toUrl());
            default:
                return $http->$method($request->getNormalizedHttpUrl(), $request->toPostData());
        }
    }
}
