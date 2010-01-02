<?php
require_once dirname(__FILE__).'/../../vendor/OAuth/OAuth.php';

/**
 * Twitter_Auth_OAuth class
 * 
 * PHP versions 5
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */
class Twitter_Auth_OAuth
{
	protected $_requestTokenUrl = 'http://twitter.com/oauth/request_token';
	protected $_accessTokenUrl = 'http://twitter.com/oauth/access_token';
	protected $_authorizeUrl = 'http://twitter.com/oauth/authorize';

	protected $_signatureMethod = null;
	protected $_consumer = null;
	protected $_token = null;

	function __construct($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
	{
		$this->_signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
		$this->_consumer = new OAuthConsumer($consumerKey, $consumerSecret);
		if (!empty($oauthToken) && !empty($oauthTokenSecret)) {
			$this->_token = new OAuthConsumer($oauthToken, $oauthTokenSecret);
		}
	}

	public function getRequestToken($callback = null)
	{
		$params = array();
		if (!empty($callback)) $params['oauth_callback'] = $callback;
		$response = $this->_request($this->_requestTokenUrl, 'GET', $params);
		$token = OAuthUtil::parse_parameters($response);
		$this->_token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
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
		$token = OAuthUtil::parse_parameters($response);
		$this->_token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}
	
	public function sign(array $data)
	{
		$signedData = $data;
		$signedData['headers'] = array('Expect:');
		
		$req = OAuthRequest::from_consumer_and_token($this->_consumer, $this->_token, strtoupper($data['method']), $data['url'], $data['params']);
		$req->sign_request($this->_signatureMethod, $this->_consumer, $this->_token);

		$method = strtolower($data['method']);
		switch ($method) {
			case 'get':
				$signedData['url'] = $req->to_url();
				$signedData['params'] = array();
				break;
			default:
				$signedData['url'] = $req->get_normalized_http_url();
				$signedData['post_data'] = $req->to_postdata();
				break;
		}
		return $signedData;
	}
	
	private function _request($url, $method, $params)
	{
		$request = OAuthRequest::from_consumer_and_token($this->_consumer, $this->_token, $method, $url, $params);
		$request->sign_request($this->_signatureMethod, $this->_consumer, $this->_token);

		$http = new Twitter_Request();
		$method = strtolower($method);
		switch ($method) {
			case 'get':
				return $http->get($request->to_url());
			default:
				return $http->$method($request->get_normalized_http_url(), $request->to_postdata());
		}
	}
}