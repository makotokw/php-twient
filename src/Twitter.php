<?php
/**
 * Twitter class
 * This file is part of the Makotokw\Twient package.
 *
 * @author      Makoto Kawasaki <makoto.kw@gmail.com>
 * @version     0.7
 * @license     The MIT License
 * @link        http://github.com/makotokw/php-twient
 */
namespace Makotokw\Twient;

use Makotokw\Twient\Auth\BasicAuth;
use Makotokw\Twient\Request\StreamingRequest;
use Makotokw\Twient\Auth\OAuth;

class Twitter
{
    const NAME = 'php-twient';
    const VERSION = '0.7';
    const URL = 'https://twitter.com';

    const REST_API_URL = 'https://api.twitter.com/1.1';
    const UPLOAD_API_URL = 'https://upload.twitter.com/1.1';
    const STREAM_URL = 'https://stream.twitter.com/1.1';
    const USER_STREAM_URL = 'https://userstream.twitter.com/1.1';
    const SITE_STREAM_URL = 'https://sitestream.twitter.com/1.1';

    protected $userAgent = null;
    protected $request = null;
    protected $auth = null;
    protected $requestClass = '\Makotokw\Twient\Request\BaseRequest';
    protected $assoc = true;

    /**
     * Streaming APIs
     * @var array
     */
    protected $streamingApis;

    /**
     * @var array
     */
    protected $defaultApiOptions;

    /**
     * @var string[]
     */
    protected $postMethodNames;

    /**
     * @var string[]
     */
    protected $deleteMethodNames;

    /**
     * @var string[]
     */
    protected $uploadMethodNames;

    /**
     * Construct
     */
    public function __construct()
    {
        if (function_exists('curl_init')) {
            $this->requestClass = '\Makotokw\Twient\Request\CurlRequest';
        }

        $this->defaultApiOptions = array(
            'url' => self::REST_API_URL,
            'required' => array(),
            '#id' => null,
            'http' => 'get',
            'streaming' => false,
        );

        $this->postMethodNames = array(
            'account/remove_profile_banner',
            'account/settings',
            'account/update_profile',
            'account/update_profile_background_image',
            'account/update_profile_colors',
            'account/update_profile_image',
            'blocks/create',
            'blocks/destroy',
            'collections/create',
            'collections/destroy',
            'collections/entries/add',
            'collections/entries/curate',
            'collections/entries/move',
            'collections/entries/remove',
            'collections/update',
            'direct_messages/destroy',
            'direct_messages/events/new',
            'direct_messages/new',
            'direct_messages/welcome_messages/new',
            'direct_messages/welcome_messages/rules/new',
            'favorites/create',
            'favorites/destroy',
            'friendships/create',
            'friendships/destroy',
            'friendships/update',
            'geo/place',
            'lists/create',
            'lists/destroy',
            'lists/members/create',
            'lists/members/create_all',
            'lists/members/destroy',
            'lists/members/destroy_all',
            'lists/subscribers/create',
            'lists/subscribers/destroy',
            'lists/update',
            'mutes/users/create',
            'mutes/users/destroy',
            'saved_searches/create',
            'saved_searches/destroy/:id',
            'statuses/destroy/:id',
            'statuses/retweet/:id',
            'statuses/unretweet/:id',
            'statuses/update',
            'statuses/update_with_media', // (deprecated)
            'users/report_spam',
        );

        $this->deleteMethodNames = array(
            'direct_messages/welcome_messages/destroy',
            'direct_messages/welcome_messages/rules/destroy',
        );

        $this->uploadMethodNames = array(
            'media/upload',
        );

        $this->streamingApis = array(
            'spritzer' => array('url' => self::STREAM_URL),
            'statuses/filter' => array('url' => self::STREAM_URL, 'http' => 'post'),
            'statuses/firehose' => array('url' => self::STREAM_URL),
            'statuses/retweet' => array('url' => self::STREAM_URL),
            'statuses/sample' => array('url' => self::STREAM_URL),
            'user' => array('url' => self::USER_STREAM_URL),
            'site' => array('url' => self::SITE_STREAM_URL),
        );
    }

    /**
     * create Twitter Request object
     * @param string $className '\Makotokw\Twient\Request\BaseRequest' or '\Makotokw\Twient\Request\CurlRequest'
     * @return \Makotokw\Twient\Request\BaseRequest
     */
    public function createRequest($className = null)
    {
        return ($className != null) ? new $className : new $this->requestClass;
    }

    /**
     * Sets a request class name
     * @param string $className     '\Makotokw\Twient\Request\BaseRequest' or '\Makotokw\Twient\Request\CurlRequest'
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
     * @deprecated Basic Auth is not longer supported
     */
    public function basicAuth($username, $password)
    {
        $this->auth = new BasicAuth($username, $password);
        return $this->auth;
    }

    /**
     * Sets up an OAuth
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @return \Makotokw\Twient\Auth\OAuth
     */
    public function oAuth($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
    {
        $this->auth = new OAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
        return $this->auth;
    }

    /**
     * Sets an auth
     * @param \Makotokw\Twient\Auth\AuthInterface $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    /**
     * Gets an auth
     * @return \Makotokw\Twient\Auth\AuthInterface
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Creates an option by methodName
     * @param string $methodName
     * @return array
     */
    public function createConfigByMethodName($methodName)
    {
        $options = array();
        if (preg_match_all('/:([\w]+)/', $methodName, $matches)) {
            for ($i = 1; $i < count($matches); $i++) {
                $options['$'.$i] = $matches[$i][0];
            }
        }
        if (in_array($methodName, $this->uploadMethodNames)) {
            $options['url'] = self::UPLOAD_API_URL;
        }
        return $options;
    }

    /**
     * Calls the REST API Method by using HTTP GET
     * @param string $methodName        method name (ie. statuses/home_timeline)
     * @param array $params             key-value parameter for method
     * @return array
     */
    public function get($methodName, array $params = array())
    {
        $options = array_merge($this->defaultApiOptions, $this->createConfigByMethodName($methodName));
        $options['http'] = 'get';
        return $this->internalCall($methodName, $options, $params);
    }

    /**
     * Calls the REST API Method by using HTTP POST
     * @param string $methodName        method name (ie. statuses/home_timeline)
     * @param array $params             key-value parameter for method
     * @return array
     */
    public function post($methodName, array $params = array())
    {
        $options = array_merge($this->defaultApiOptions, $this->createConfigByMethodName($methodName));
        $options['http'] = 'post';
        return $this->internalCall($methodName, $options, $params);
    }

    /**
     * Calls the REST API Method
     * @param string $methodName
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function call($methodName, array $params = array())
    {
        $options = array_merge($this->defaultApiOptions, $this->createConfigByMethodName($methodName));
        if (in_array($methodName, $this->postMethodNames)) {
            if (!($methodName == 'account/settings' && empty($params))) {
                $options['http'] = 'post';
            }
        }
        return $this->internalCall($methodName, $options, $params);
    }

    /**
     * Calls the REST API Method
     * @param string $methodName
     * @param array $options
     * @param array $params
     * @return array
     * @throws Exception
     */
    protected function internalCall($methodName, array $options, array $params)
    {
        foreach ($options['required'] as $key) {
            if (!array_key_exists($key, $params)) {
                throw new Exception($key . ' is required');
            }
        }

        // make url {method}.{format} or {method}/{id}.{format}
        $method = (isset($options['method'])) ? $options['method'] : $methodName;
        $format = 'json';
        $key = @$options['$1'];
        $id = ($key != null && array_key_exists($key, $params)) ? $params[$key] : null;
        if ($id != null) {
            unset($params[$key]);
            if (false !== strpos($method, ':' . $key)) {
                $method = str_replace(':' . $key, $id, $method);
                $url = sprintf('%s/%s.%s', $options['url'], $method, $format);
            } else {
                $url = sprintf('%s/%s/%s.%s', $options['url'], $method, $id, $format);
            }
        } else {
            $url = sprintf('%s/%s.%s', $options['url'], $method, $format);
        }

        $request = $this->createRequest();
        $request->useAssociativeArray($this->assoc);
        $request->setUserAgent($this->getUserAgent());
        $method = $options['http'] . 'JSON';
        return $request->$method($url, $params, $this->getAuth());
    }

    /**
     * Calls the Streaming API Method
     * @param string $methodName method name (ie. statuses/sample)
     * @param array $params key-value parameter for method
     * @param callable $callback callback function (ie. "function_name" or array(class, "method_name") or closure)
     * @return mixed
     * @throws Exception
     */
    public function streaming($methodName, array $params, callable $callback)
    {
        $config = array_merge($this->defaultApiOptions, $this->streamingApis[$methodName]);

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

        if (!$callback) {
            throw new Exception('callback is required');
        }
        if (!is_callable($callback)) {
            throw new Exception('callback is not callabled');
        }
        $request = new StreamingRequest();
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
