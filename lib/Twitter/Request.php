<?php
/**
 * Twitter_Request class
 * 
 * PHP versions 5
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */
class Twitter_Request
{
	protected $_core;
	protected $_headers = array();
	protected $_userAgent;
	protected $_timeout = 30;
	protected $_assoc = true;

	/**
	 *	@return Twitter 
	 */
	public function getCore()
	{
		return $this->_core;
	}

	/**
	 * @param Twitter $core
	 */
	public function setCore(Twitter $core)
	{
		$this->_core = $core;
	}
	
	/**
	 * Gets the user agent for the HTTP Request to twitter
	 * @return string user agent
	 */
	public function getUserAgent()
	{
		return (!empty($this->_userAgent)) ? $this->_userAgent : Twitter::NAME.'/'.Twitter::VERSION;
	}

	/**
	 * Sets a user agent for the HTTP Request to twitter
	 * @param string $userAgent user agent
	 */
	public function setUserAgent($userAgent)
	{
		$this->_userAgent = (string)$userAgent;
	}
	
	public function useAssociativeArray($assoc = true)
	{
		$this->_assoc = (bool)$assoc;
	}
	
	public function getTimeout()
	{
		return $this->_timeout;
	}
	
	public function setTimeout($timeout)
	{
		$this->_timeout = (int)$timeout;
	}
	
	public function close()
	{
		$this->_headers = array();
	}
	
	public function addHeader(array $headers = array())
	{
		$this->_headers = array_merge($this->_headers, $headers);
	}
	
	public function get($url, array $params = array(), $auth = false)
	{
		$method = 'GET';
		$headers = array();
		if ($auth) {
			$signedData = $auth->sign(array('url'=>$url, 'method'=>$method, 'params'=>$params));
			$url = $signedData['url'];
			$params = $signedData['params'];
			if (isset($signedData['headers'])) {
				$headers = array_merge($headers, $signedData['headers']);
			}
		} else {
			if (count($params)) {
				$url .= ((strpos($url,'?')===false) ? '?' : '&').http_build_query($params);
			}
		}
		return $this->_request($url, $method, $headers);
	}
	
	public function post($url, array $params = array(), $auth = false)
	{
		$method = 'POST';
		$headers = array();
		$postData = null;
		if ($auth) {
			$signedData = $auth->sign(array('url'=>$url, 'method'=>$method, 'params'=>$params));
			$url = $signedData['url'];
			$postData = $signedData['post_data'];
			if (isset($signedData['headers'])) {
				$headers = array_merge($headers, $signedData['headers']);
			}
		} else {
			$postData = http_build_query($params);
		}
		return $this->_request($url, $method, $headers, $postData);
	}
	
	protected function _request($url, $method, $headers = array(), $postData = NULL)
	{

		$headers = array_merge($headers, array(
			'User-Agent: '.$this->getUserAgent(),
			));

		if ($postData) {
			$headers = array_merge($headers, array(
				'Content-Type: application/x-www-form-urlencoded',
				'Content-Length: '.strlen($postData)
				));
		}

		$opt = array(
			'http' => array(
				'protocol_version'=>'1.1',
				'method'        => $method,
				'header'        => implode("\r\n", $headers),
				'ignore_errors' => true,
				)
			);

		if ($postData) {
			$opt['http']['content'] = $postData;
		}

		$contents = file_get_contents($url, false, stream_context_create($opt));
		list ($version, $statusCode, $msg) = explode(' ', $http_response_header[0], 3);
		if ($statusCode != 200) {
			throw new Twitter_Exception($msg, $statusCode, $contents);
		}
		return $contents;
	}
	
	public function getJSON($url, array $params = array(), $auth = false, $callback = null)
	{
		return json_decode($this->get($url, $params, $auth), $this->_assoc);
	}

	public function postJSON($url, array $params = array(), $auth = false, $callback = null)
	{
		return json_decode($this->post($url, $params, $auth), $this->_assoc);
	}
}

// from http://php.net/manual/en/function.json-decode.php
if (!function_exists('json_decode')) {
	if (!class_exists('Services_JSON')) require_once dirname(__FILE__).'/../vendor/Services_JSON/JSON.php';
	function json_decode($content, $assoc=false)
	{
		$json = new Services_JSON(($assoc) ? SERVICES_JSON_LOOSE_TYPE : 0);
		return $json->decode($content);
	}
}