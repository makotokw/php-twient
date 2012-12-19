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
	protected function _request($url, $method, $headers = array(), $postData = NULL)
	{
		if (!function_exists('curl_init')) {
			throw new Twitter_Exception('php_curl is not found');
		}

		$ci = curl_init();

		$headers = array_merge($headers, array(
			// disable Expect: 100-continue
			// cURL add 100-continue when postData is over 1024 chars
			'Expect:',
			'User-Agent: '.$this->getUserAgent(),
		));

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
		curl_close($ci);
		
		if ($httpCode != 200) {
			throw new Twitter_Exception(null, $httpCode, $response);
		}
		
		return $response;
	}
}