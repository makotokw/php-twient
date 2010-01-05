<?php
/**
 * Twitter_Request_Curl class
 * 
 * PHP versions 5
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */
class Twitter_Request_Curl extends Twitter_Request
{
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
	
	private function _request($url, $method, $headers = array(), $postData = NULL)
	{
		$ci = curl_init();
		
		curl_setopt($ci, CURLOPT_USERAGENT, $this->getUserAgent());
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->getTimeout());
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->getTimeout());
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postData)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postData);
				}
				break;
		}

		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$httpCode = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		curl_close ($ci);
		
		if ($httpCode!=200) throw new Twitter_Exception(null, $httpCode);
		
		return $response;
	}
}