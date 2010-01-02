<?php
/**
 * Twitter_TinyUrl class
 * 
 * PHP versions 5
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */
class Twitter_TinyUrl
{
	const URL = 'http://tinyurl.com/api-create.php';
	
	/**
	 * Creates tiny url
	 * @param string $url
	 * @return string tiny url
	 */
	static function create($url)
	{
		$req = new Twitter_Request();
		return $req->get(self::URL,array('url'=>$url));
	}
}
