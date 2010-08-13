<?php
/**
 * Twitter_Request_Streaming class
 * 
 * PHP versions 5
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */
class Twitter_Request_Streaming extends Twitter_Request
{
	protected function _streaming($stream, $callback)
	{ 
		$count = 0;
		while ($data = fgets($stream)) {
			$count++;
			$status = json_decode($data, $this->_assoc);
			if (!call_user_func($callback, $status)) {
				break;
			}
		}
		return $count;
	}
	
	public function getJSON($url, array $params = array(), $auth = false, $callback = null)
	{
		$method = 'GET';
		$headers = array(
			'User-Agent: '.$this->getUserAgent(),
		);
		
		$signedData = $auth->sign(array('url'=>$url, 'method'=>$method, 'params'=>$params));
		$url = $signedData['url'];
		$params = $signedData['params'];
		if (isset($signedData['headers'])) {
			$headers = array_merge($headers, $signedData['headers']);
		}
		$opt = array(
			'http' => array(
				'method'    => 'GET',
				'header'    => implode("\r\n", $headers),
			)
		);
		return $this->_streaming(fopen($url, 'r' ,false, stream_context_create($opt)), $callback);
		
	}
	
	public function postJSON($url, array $params = array(), $auth = false, $callback = null)
	{
		$method = 'POST';
		$headers = array(
			'User-Agent: '.$this->getUserAgent(),
		);
		
		$signedData = $auth->sign(array('url'=>$url, 'method'=>$method, 'params'=>$params));
		$url = $signedData['url'];
		$content = $signedData['post_data'];
		if (isset($signedData['headers'])) {
			$headers = array_merge($headers, $signedData['headers']);
		}

		$headers = array_merge($headers, array(
				'Content-Type: application/x-www-form-urlencoded',
				'Content-Length: '.strlen($content),
		));
		
		$opt = array(
			'http' => array(
				'method'    => $method,
				'header'    => implode("\r\n", $headers),
				'content'   => $content
			)
		);
		return $this->_streaming(fopen($url, 'r' ,false, stream_context_create($opt)), $callback);
	}
}