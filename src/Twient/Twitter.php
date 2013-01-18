<?php
/**
 * Twient\Twitter class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @version    0.4
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 * @link       http://github.com/makotokw/php-twient
 */

namespace Twient;

class Twitter
{
    const NAME = 'php-twient';
    const VERSION = '0.4';
    const URL = 'http://twitter.com';
    const API_URL = 'http://api.twitter.com/1';
    const SEARCH_URL = 'http://search.twitter.com';
    const STREAM_URL = 'https://stream.twitter.com/1';
    const USER_STREAM_URL = 'https://userstream.twitter.com/2';

    public $apis;
    public $streamingApis;

    static protected $path = null;
    protected $userAgent = null;
    protected $request = null;
    protected $auth = null;
    protected $requestClass = 'Twitter_Request';
    protected $assoc = true;
    protected $defaultConfigration;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->requestClass = (function_exists(
            'curl_init'
        )) ? '\Twient\Request\CurlRequest' : '\Twient\Request\BaseRequest';
        $this->defaultConfigration = array(
            'url' => self::API_URL,
            'required' => array(),
            '#id' => null,
            'http' => 'get',
            'auth' => true,
            'streaming' => false,
        );
        // https://dev.twitter.com/docs/api/1
        $this->apis = array(

            // Timelines
            'statuses/home_timeline' => array(),
            'statuses/mentions' => array(),
            'statuses/retweeted_by_me' => array(),
            'statuses/retweeted_to_me' => array(),
            'statuses/retweets_of_me' => array(),
            'statuses/user_timeline' => array(),
            'statuses/retweeted_to_user' => array(),
            'statuses/retweeted_by_user' => array(),
            // Tweets
            'statuses/:id/retweeted_by' => array('required' => array('id'), '$1' => 'id'),
            'statuses/:id/retweeted_by/ids' => array('required' => array('id'), '$1' => 'id'),
            'statuses/retweets/:id' => array('required' => array('id'), '$1' => 'id'),
            'statuses/show/:id' => array('required' => array('id'), '$1' => 'id', 'auth' => false),
            'statuses/destroy/:id' => array('required' => array('id'), '$1' => 'id', 'http' => 'post'),
            'statuses/retweet/:id' => array('required' => array('id'), '$1' => 'id', 'http' => 'post'),
            'statuses/update' => array('required' => array('status'), 'http' => 'post'),
            //statuses/update_with_media
            //statuses/oembed

            // Search
            'search' => array('url' => self::SEARCH_URL, 'required' => array('q'), 'auth' => false),
            // Direct Messages

            // Friends & Followers
            'followers/ids' => array('auth' => false),
            'friends/ids' => array('auth' => false),
            'friendships/exists' => array('required' => array('user_a', 'user_b')),
            'friendships/incoming' => array(),
            'friendships/outgoing' => array(),
            'friendships/show' => array(),
            'friendships/create' => array('http' => 'post'),
            'friendships/destroy' => array('http' => 'post'),
            'friendships/lookup' => array(),
            'friendships/update' => array('http' => 'post'),
            'friendships/no_retweet_ids' => array(),
            // Users
            //users/profile_image/:screen_name
            'users/search' => array('required' => array('q')),
            'users/show' => array('$1' => 'id', 'auth' => false),
            //users/contributees
            //users/contributors

            // Suggested Users
            'users/suggestions' => array(),
            'users/suggestions/:slug' => array('$1' => 'slug', 'required' => array('slug')),
            'users/suggestions/:slug/members' => array('$1' => 'slug', 'required' => array('slug')),
            // Favorites
            // Lists
            // Accounts
            // Notification
            // Saved Searches
            // Places & Geo

            // Trends
            'trends/:woeid' => array('$1' => 'woeid', 'required' => array('woeid'), 'auth' => false),
            'trends/available' => array('auth' => false),
            'trends/daily' => array('auth' => false),
            'trends/weekly' => array('auth' => false),

            // Block
            // Spam Reporting
            // OAuth
            // Help
            // Legal
        );

        $this->streamingApis = array(
            'spritzer' => array('url' => self::STREAM_URL, 'streaming' => true),
            'statuses/filter' => array('url' => self::STREAM_URL, 'streaming' => true, 'http' => 'post'),
            'statuses/firehose' => array('url' => self::STREAM_URL, 'streaming' => true),
            'statuses/retweet' => array('url' => self::STREAM_URL, 'streaming' => true),
            'statuses/sample' => array('url' => self::STREAM_URL, 'streaming' => true),
            'user' => array('url' => self::USER_STREAM_URL, 'streaming' => true),
        );
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
     * @param string $className '\Twient\Request\BaseRequest' or '\Twient\Request\CurlRequest'
     * @return \Twient\Request\BaseRequest
     */
    public function createRequest($className = null)
    {
        return ($className != null) ? new $className : new $this->requestClass;
    }

    /**
     * Sets a request class name
     * @param string $className     '\Twient\Request\BaseRequest' or '\Twient\Request\CurlRequest'
     */
    public function setRequestClass($className)
    {
        if (class_exists($className)) {
            $this->requestClass = (string)$className;
        }
    }

    /**
     * Gets the request class name
     * @return string
     */
    public function getRequestClass()
    {
        return $this->requestClass;
    }

    /**
     * Sets up a Basic Auth
     * @param string $username    e-mail or account for twitter
     * @param string $password    password for twitter
     * @return Auth\BasicAuth
     */
    public function basicAuth($username, $password)
    {
        $this->auth = new \Twient\Auth\BasicAuth($username, $password);
        return $this->auth;
    }

    /**
     * Sets up an OAuth
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @return Auth\OAuth
     */
    public function oAuth($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
    {
        $this->auth = new \Twient\Auth\OAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
        return $this->auth;
    }

    /**
     * Sets an auth
     * @param \Twient\Auth\AuthInterface $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    /**
     * Gets an auth
     * @return \Twient\Auth\AuthInterface
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Calls the REST API Method
     * @param string $methodName    method name (ie. statuses/public_timeline)
     * @param array $params            key-value parameter for method
     * @throws Exception
     */
    public function call($methodName, array $params = array())
    {
        if (!array_key_exists($methodName, $this->apis)) {
            throw new Exception('This method is not supported: ' . $methodName);
        }
        $config = array_merge($this->defaultConfigration, $this->apis[$methodName]);

        foreach ($config['required'] as $key) {
            if (!array_key_exists($key, $params)) {
                throw new Exception($key . ' is required');
            }
        }

        // make url {method}.{format} or {method}/{id}.{format}
        $method = (isset($config['method'])) ? $config['method'] : $methodName;
        $format = 'json';
        $key = @$config['$1'];
        $id = ($key != null && array_key_exists($key, $params)) ? $params[$key] : null;
        if ($id != null) {
            unset($params[$key]);
            if (false !== strpos($method, ':' . $key)) {
                $method = str_replace(':' . $key, $id, $method);
                $url = sprintf('%s/%s.%s', $config['url'], $method, $format);
            } else {
                $url = sprintf('%s/%s/%s.%s', $config['url'], $method, $id, $format);
            }
        } else {
            $url = sprintf('%s/%s.%s', $config['url'], $method, $format);
        }

        $request = $this->createRequest();
        $request->useAssociativeArray($this->assoc);
        $request->setUserAgent($this->getUserAgent());
        $method = $config['http'] . 'JSON';
        return $request->$method($url, $params, ($config['auth']) ? $this->getAuth() : null);
    }

    /**
     * Calls the Streaming API Method
     * @param string $methodName    method name (ie. statuses/sample)
     * @param array $params            key-value parameter for method
     * @param mixed $callback        callback function (ie. "function_name" or array(class, "method_name"))
     * @return mixed
     * @throws Exception
     */
    public function streaming($methodName, array $params = array(), $callback = null)
    {
        if (!array_key_exists($methodName, $this->streamingApis)) {
            throw new Exception('This method is not supported: ' . $methodName);
        }
        $config = array_merge($this->defaultConfigration, $this->streamingApis[$methodName]);

        foreach ($config['required'] as $key) {
            if (!array_key_exists($key, $params)) {
                throw new Exception($key . ' is required');
            }
        }

        // make url {method}.{format} or {method}/{id}.{format}
        $method = (isset($config['method'])) ? $config['method'] : $methodName;
        $format = 'json';
        $key = $config['#id'];
        $id = ($key != null && array_key_exists($key, $params)) ? $params[$key] : null;
        if ($id != null) {
            unset($params[$key]);
            $url = sprintf('%s/%s/%s.%s', $config['url'], $method, $id, $format);
        } else {
            $url = sprintf('%s/%s.%s', $config['url'], $method, $format);
        }

        // if (get_class($this->getAuth()) != 'Twitter_Auth_Basic') {
        // 	throw new Twitter_Exception('Streaming API requires BasicAuth!');
        // }
        if (!$callback) {
            throw new Exception('callback is required');
        }
        if (!is_callable($callback)) {
            throw new Exception('callback is not callabled');
        }
        $request = new \Twient\Request\StreamingRequest();
        $request->setCore($this);
        $request->useAssociativeArray($this->assoc);
        $request->setUserAgent($this->getUserAgent());
        $method = $config['http'] . 'JSON';
        return $request->$method($url, $params, $this->getAuth(), $callback);
    }

    /**
     * Gets a user agent for the HTTP Request to twitter
     * @return string user agent
     */
    public function getUserAgent()
    {
        return (!empty($this->userAgent)) ? $this->userAgent : self::NAME . '/' . self::VERSION;
    }

    /**
     * Sets a user agent for the HTTP Request to twitter
     * @param string $userAgent user agent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = (string)$userAgent;
    }

    /**
     * returned objects or associative arrays
     * @param bool $assoc When TRUE, call method returned objects will be converted into associative arrays.
     */
    public function useAssociativeArray($assoc = true)
    {
        $this->assoc = (bool)$assoc;
    }
}
