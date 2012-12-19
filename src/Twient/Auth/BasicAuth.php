<?php
/**
 * Twient\Auth\BasicAuth class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */

namespace Twient\Auth;

class BasicAuth implements AuthInterface
{
	protected $_username;
	protected $_password;
	
	public function __construct($username = null, $password = null)
	{
		$this->_username = $username;
		$this->_password = $password;
	}
	
	public function sign(array $data)
	{
		$signedData = $data;
		$signedData['headers'] = array('Authorization: Basic '.base64_encode($this->_username.':'.$this->_password));
		$method = strtolower($data['method']);
		switch ($method) {
			case 'get':
				$signedData['url'] .= '?'.http_build_query($data['params']);
				break;
			default:
				$signedData['post_data'] = http_build_query($data['params']);
				break;
		}
		return $signedData;
	}
}