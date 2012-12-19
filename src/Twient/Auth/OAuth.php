<?php
/**
 * Twient\Auth\OAuth class
 * This file is part of the Twient package.
 * 
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */

namespace Twient\Auth;

class OAuth implements AuthInterface
{
	protected $_requestTokenUrl = 'http://twitter.com/oauth/request_token';
	protected $_accessTokenUrl = 'http://twitter.com/oauth/access_token';
	protected $_authorizeUrl = 'http://twitter.com/oauth/authorize';

	protected $_signatureMethod = null;
	protected $_consumer = null;
	protected $_token = null;

	function __construct($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
	{
		$this->_signatureMethod = new \Twient\Auth\OAuth\HMACSHA1SignatureMethod();
		$this->_consumer = new \Twient\Auth\OAuth\Consumer($consumerKey, $consumerSecret);
		if (!empty($oauthToken) && !empty($oauthTokenSecret)) {
			$this->_token = new \Twient\Auth\OAuth\Consumer($oauthToken, $oauthTokenSecret);
		}
	}

	public function getRequestToken($callback = null)
	{
		$params = array();
		if (!empty($callback)) $params['oauth_callback'] = $callback;
		$response = $this->_request($this->_requestTokenUrl, 'GET', $params);
		$token = \Twient\Auth\OAuth\Util::parseParameters($response);
		$this->_token = new \Twient\Auth\OAuth\Consumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	public function getAuthorizeUrl($token)
	{
		if (is_array($token)) $token = $token['oauth_token'];
		return $this->_authorizeUrl.'?oauth_token='.$token;
	}

	public function getAccessToken($verifier = false)
	{
		$params = array();
		if (!empty($verifier)) $params['oauth_verifier'] = $verifier;
		$response = $this->_request($this->_accessTokenUrl, 'GET', $params);
		$token = \Twient\Auth\OAuth\Util::parseParameters($response);
		$this->_token = new \Twient\Auth\OAuth\Consumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}
	
	public function sign(array $data)
	{
		$signedData = $data;
		$signedData['headers'] = array();
		
		$req = \Twient\Auth\OAuth\Request::fromConsumerAndToken($this->_consumer, $this->_token, strtoupper($data['method']), $data['url'], $data['params']);
		$req->sign_request($this->_signatureMethod, $this->_consumer, $this->_token);

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
	
	private function _request($url, $method, $params)
	{
		$request = \Twient\Auth\OAuth\Request::fromConsumerAndToken($this->_consumer, $this->_token, $method, $url, $params);
		$request->sign_request($this->_signatureMethod, $this->_consumer, $this->_token);

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