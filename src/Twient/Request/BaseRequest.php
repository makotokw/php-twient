<?php
/**
 * Twient\Request\BaseRequest class
 * This file is part of the Twient package.
 * 
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */

namespace Twient\Request;

use Twient\Twitter;

class BaseRequest
{
	/**
	 * @var Twitter
	 */
	protected $_core;

	/**
	 * @var array
	 */
	protected $_headers = array();

	/**
	 * @var string
	 */
	protected $_userAgent;

	/**
	 * @var int
	 */
	protected $_timeout = 30;

	/**
	 * @var bool
	 */
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

	/**
	 * @param $url
	 * @param array $params
	 * @param \Twient\Auth\AuthInterface $auth
	 * @return string
	 */
	public function get($url, array $params = array(), $auth = null)
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

	/**
	 * @param $url
	 * @param array $params
	 * @param \Twient\Auth\AuthInterface $auth
	 * @return string
	 */
	public function post($url, array $params = array(), $auth = null)
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

	/**
	 * @param $url
	 * @param $method
	 * @param array $headers
	 * @param null $postData
	 * @return string
	 * @throws \Twient\Exception
	 */
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
			$msg .= ',url='.$url;
			throw new \Twient\Exception($msg, $statusCode, $contents);
		}
		return $contents;
	}

	/**
	 * @param $url
	 * @param array $params
	 * @param bool $auth
	 * @param null $callback
	 * @return mixed
	 */
	public function getJSON($url, array $params = array(), $auth = false, $callback = null)
	{
		return json_decode($this->get($url, $params, $auth), $this->_assoc);
	}

	/**
	 * @param $url
	 * @param array $params
	 * @param bool $auth
	 * @param null $callback
	 * @return mixed
	 */
	public function postJSON($url, array $params = array(), $auth = false, $callback = null)
	{
		return json_decode($this->post($url, $params, $auth), $this->_assoc);
	}
}