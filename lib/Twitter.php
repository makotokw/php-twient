<?php
/**
 * Twitter class
 * 
 * PHP versions 5
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @version    0.2
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 * @link       http://github.com/makotokw/php-twient
 */
class Twitter
{
	const NAME = 'php-twient';
	const VERSION = '0.3';
	const URL = 'http://twitter.com';
	const API_URL = 'http://api.twitter.com/1';
	const SEARCH_URL = 'http://search.twitter.com';
	const STREAM_URL = 'http://stream.twitter.com/1';
	
	public $apis;
	public $streamingApis;
	
	static protected $_path = null;
	protected $_userAgent = null;
	protected $_request = null;
	protected $_auth = null;
	protected $_requestClass = 'Twitter_Request';
	protected $_assoc = true;
	protected $_defaultConfigration;
	
	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->_requestClass = (function_exists('curl_init')) ? 'Twitter_Request_Curl' : 'Twitter_Request';
		$this->_defaultConfigration = array(
			'url'=>self::API_URL,
			'required'=>array(),
			'#id'=>null,
			'http'=>'get',
			'auth'=>true,
			'streaming'=>false,
		);
		// http://apiwiki.twitter.com/Twitter-API-Documentation
		$this->apis = array(
			// Search Methods
			'search'					=> array('url'=>self::SEARCH_URL,'required'=>array('q'),'auth'=>false),
		
			// Timeline Methods
			'statuses/public_timeline'	=> array('auth'=>false),
			'statuses/friends_timeline'	=> array(),
			'statuses/home_timeline'	=> array(),
			'statuses/user_timeline'	=> array('#id'=>'id'),
			'statuses/mentions'			=> array(),
			'statuses/retweeted_by_me'	=> array(),
			'statuses/retweeted_to_me'	=> array(),
			'statuses/retweets_of_me'	=> array(),
			
			// Status Methods
			'statuses/show'					=> array('required'=>array('id'),'#id'=>'id','auth'=>false),
			'statuses/update'				=> array('required'=>array('status'),'http'=>'post'),
			'statuses/destroy'				=> array('required'=>array('id'),'#id'=>'id','http'=>'post'), 
			'statuses/retweet'				=> array('required'=>array('id'),'#id'=>'id','http'=>'post'),
			'statuses/retweets'				=> array('required'=>array('id'),'#id'=>'id'),
			'statuses/id/retweeted_by'		=> array('method'=>'statuses/#id/retweeted_by','required'=>array('id'),'#id'=>'id'),
			'statuses/id/retweeted_by/ids'	=> array('method'=>'statuses/#id/retweeted_by/ids','required'=>array('id'),'#id'=>'id'),
		
			// User Methods
			'users/show'					=> array('#id'=>'id','auth'=>false),
			'users/lookup'					=> array(),
			'users/search'					=> array('required'=>array('q')),
			'users/suggestions'				=> array(),
			'users/suggestions/category'	=> array('method'=>'users/suggestions','#id'=>'slug','required'=>array('slug')),
			'statuses/friends'				=> array('#id'=>'id','auth'=>false),
			'statuses/followers'			=> array('#id'=>'id','auth'=>false),
		
			// List Methods
			// List Members Methods
			// List Subscribers Methods
			// Direct Message Methods 
		
			// Friendship Methods
			'friendships/create'	=> array('#id'=>'id','http'=>'post'),
			'friendships/destroy'	=> array('#id'=>'id','http'=>'post'),
			'friendships/exists'	=> array('required'=>array('user_a','user_b')),
			'friendships/show'		=> array('#id'=>'id'),
		
			// Social Graph Methods
			'friends/ids'	=> array('#id'=>'id','auth'=>false),
			'followers/ids'	=> array('#id'=>'id','auth'=>false),
		
			// Account Methods
			// Favorite Methods
			// Notification Methods
			// Block Methods
			// Spam Reporting Methods
			// Saved Searches Methods
			// OAuth Methods
		
			// Trends Methods
			'trends/available'	=> array('auth'=>false),
			'trends/locations'	=> array('method'=>'trends','#id'=>'woeid','required'=>array('woeid'),'auth'=>false),
			'trends'			=> array('auth'=>false),
			'trends/current'	=> array('auth'=>false),
			'trends/daily'		=> array('auth'=>false),
			'trends/weekly'		=> array('auth'=>false),
		
			// Geo methods
			// Help Methods
		);
	
		$this->streamingApis = array(
			'spritzer' => array('url'=>self::STREAM_URL,'streaming'=>true),
			'statuses/filter' => array('url'=>self::STREAM_URL,'http'=>'post','streaming'=>true),
			'statuses/firehose' => array('url'=>self::STREAM_URL,'streaming'=>true),
			'statuses/retweet' => array('url'=>self::STREAM_URL,'streaming'=>true),
			'statuses/sample' => array('url'=>self::STREAM_URL,'streaming'=>true),
		);
	}
	
	/**
	 * Gets the path
	 *
	 * @return string
	 */
	public static function getPath()
	{
		if (!self::$_path) {
			self::$_path = dirname(__FILE__);
		}
		return self::$_path;
	}

	/**
	 * Twitter autoload
	 *	spl_autoload_register(array('Twitter', 'autoload'));
	 * @param string $classname
	 * @return bool
	 */
	public static function autoload($className)
	{
		if (class_exists($className, false) || interface_exists($className, false)) {
			return false;
		}
		$class = self::getPath() . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
		if (file_exists($class)) {
			require $class;
			return true;
		}
		return false;
	}
	
	/**
	 * extends API
	 * @param array $apis
	 * @param array $streamingApis
	 */
	public function extend(array $apis, array $streamingApis = array())
	{
		$this->apis = array_merge($this->apis, $apis);
		$this->streamingApis = array_merge($this->streamingApis, $streamingApis);
	}
	
	/**
	 * create Twitter Request object
	 * @param string $className Twitter_Request or Twitter_Request_Curl
	 * @return Twitter_Request
	 */
	public function createRequest($className = null)
	{
		return ($className!=null) ? new $className : new $this->_requestClass;
	}
	
	/**
	 * Sets a request class name
	 * @param string $className	 Twitter_Request or Twitter_Request_Curl
	 */
	public function setRequestClass($className)
	{
		$this->_requestClass = (string)$className;;
	}
	
	/**
	 * Gets the request class name
	 * @return string
	 */
	public function getRequestClass()
	{
		return $this->_requestClass;
	}
	
	/**
	 * Sets up a Basic Auth
	 * @param string $username	e-mail or account for twitter
	 * @param string $password	password for twitter
	 * @return Twitter_Auth_Basic
	 */
	public function basicAuth($username, $password)
	{
		$this->_auth = new Twitter_Auth_Basic($username, $password);
		return $this->_auth;
	}
	
	/**
	 * Sets up an OAuth
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @param string $oauthToken
	 * @param string $oauthTokenSecret
	 * @return Twitter_Auth_OAuth
	 */
	public function oAuth($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
	{
		$this->_auth = new Twitter_Auth_OAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
		return $this->_auth;
	}
	
	/**
	 * Sets an auth
	 * @param Twitter_Auth $auth
	 */
	public function setAuth($auth)
	{
		$this->_auth = $auth;
	}
	
	/**
	 * Gets an auth
	 * @return Twitter_Auth
	 */
	public function getAuth()
	{
		return $this->_auth;
	}
	
	/**
	 * Calls the REST API Method
	 * @param string $methodName	method name (ie. statuses/public_timeline)
	 * @param array $params			key-value parameter for method
	 * @return mixed
	 */
	public function call($methodName, array $params = array())
	{
		if (!array_key_exists($methodName, $this->apis)) {
			throw new Twitter_Exception('This method is not supported: '.$methodName);
		}
		$config = array_merge($this->_defaultConfigration, $this->apis[$methodName]);
		
		foreach ($config['required'] as $key) {
			if (!array_key_exists($key, $params)) {
				throw new Twitter_Exception($key.' is required');
			}
		}
		
		// make url {method}.{format} or {method}/{id}.{format}
		$method = (isset($config['method'])) ? $config['method'] : $methodName;
		$format = 'json';
		$key = $config['#id'];
		$id = ($key!=null && array_key_exists($key,$params)) ? $params[$key] : null;
		if ($id!=null) {
			unset($params[$key]);
			if (false !== strpos($method,'#id')) {
				$method = str_replace('#id',$id,$method);
				$url = sprintf('%s/%s.%s',$config['url'], $method, $format);
			} else {
				$url = sprintf('%s/%s/%s.%s',$config['url'], $method, $id, $format);
			}
		} else {
			$url = sprintf('%s/%s.%s',$config['url'], $method, $format);
		}
		
		$request = $this->createRequest();
		$request->useAssociativeArray($this->_assoc);
		$request->setUserAgent($this->getUserAgent());
		$method = $config['http'].'JSON';
		return $request->$method($url, $params, ($config['auth']) ? $this->getAuth() : null);
	}
	
	/**
	 * Calls the Streaming API Method
	 * @param string $methodName	method name (ie. statuses/sample)
	 * @param array $params			key-value parameter for method
	 * @param mixed $callback		callback function (ie. "function_name" or array(class, "method_name"))
	 * @return mixed
	 */
	public function streaming($methodName, array $params = array(), $callback = null)
	{
		if (!array_key_exists($methodName, $this->streamingApis)) {
			throw new Twitter_Exception('This method is not supported: '.$methodName);
		}
		$config = array_merge($this->_defaultConfigration, $this->streamingApis[$methodName]);
		
		foreach ($config['required'] as $key) {
			if (!array_key_exists($key, $params)) {
				throw new Twitter_Exception($key.' is required');
			}
		}
		
		// make url {method}.{format} or {method}/{id}.{format}
		$method = (isset($config['method'])) ? $config['method'] : $methodName;
		$format = 'json';
		$key = $config['#id'];
		$id = ($key!=null && array_key_exists($key,$params)) ? $params[$key] : null;
		if ($id!=null) {
			unset($params[$key]);
			$url = sprintf('%s/%s/%s.%s',$config['url'], $method, $id, $format);
		} else {
			$url = sprintf('%s/%s.%s',$config['url'], $method, $format);
		}
		
		if (get_class($this->getAuth()) != 'Twitter_Auth_Basic') {
			throw new Twitter_Exception('Streaming API requires BasicAuth!');
		}
		if (!$callback) throw new Twitter_Exception('callback is required');
		if (!is_callable($callback)) throw new Twitter_Exception('callback is not callabled');
		$request = new Twitter_Request_Streaming();
		$request->useAssociativeArray($this->_assoc);
		$request->setUserAgent($this->getUserAgent());
		$method = $config['http'].'JSON';
		return $request->$method($url, $params, $this->getAuth(), $callback);
	}
	
	/**
	 * Gets a user agent for the HTTP Request to twitter
	 * @return string user agent
	 */
	public function getUserAgent()
	{
		return (!empty($this->_userAgent)) ? $this->_userAgent : self::NAME.'/'.self::VERSION;
	}

	/**
	 * Sets a user agent for the HTTP Request to twitter
	 * @param string $userAgent user agent
	 */
	public function setUserAgent($userAgent)
	{
		$this->_userAgent = (string)$userAgent;
	}
	
	/**
	 * returned objects or associative arrays
	 * @param bool $assoc When TRUE, call method returned objects will be converted into associative arrays.
	 * @return unknown_type
	 */
	public function useAssociativeArray($assoc = true)
	{
		$this->_assoc = (bool)$assoc;
	}
}

// include or autoload
if (function_exists('spl_autoload_register')) {
	spl_autoload_register(array('Twitter', 'autoload'));
} else {
	require_once dirname(__FILE__).'/Twitter/Exception.php';
	require_once dirname(__FILE__).'/Twitter/Auth.php';
	require_once dirname(__FILE__).'/Twitter/Auth/Basic.php';
	require_once dirname(__FILE__).'/Twitter/Auth/OAuth.php';
	require_once dirname(__FILE__).'/Twitter/Request.php';
	require_once dirname(__FILE__).'/Twitter/Request/Curl.php';
	require_once dirname(__FILE__).'/Twitter/Request/Streaming.php';
	require_once dirname(__FILE__).'/Twitter/TinyUrl.php';
}