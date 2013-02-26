<?php
/**
 * Twient\Twitter class
 * This file is part of the Twient package.
 *
 * @author      makoto_kw <makoto.kw@gmail.com>
 * @version     0.5
 * @license     The MIT License
 * @link        http://github.com/makotokw/php-twient
 */

namespace Twient;

use Twient\Request\StreamingRequest;
use Twient\Auth\OAuth;

/**
 * @method array statusesTimeline(array $params)
 */
class Twitter
{
    const NAME = 'php-twient';
    const VERSION = '0.5';
    const URL = 'http://twitter.com';

    const REST_API_URL = 'https://api.twitter.com/1.1';
    const STREAM_URL = 'https://stream.twitter.com/1.1';
    const USER_STREAM_URL = 'https://userstream.twitter.com/1.1';
    const SITE_STREAM_URL = 'https://sitestream.twitter.com/1.1';

    protected $userAgent = null;
    protected $request = null;
    protected $auth = null;
    protected $requestClass = '\Twient\Request\BaseRequest';
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
     * Construct
     */
    public function __construct()
    {
        $this->requestClass = (function_exists(
            'curl_init'
        )) ? '\Twient\Request\CurlRequest' : '\Twient\Request\BaseRequest';

        $this->defaultApiOptions = array(
            'url' => self::REST_API_URL,
            'required' => array(),
            '#id' => null,
            'http' => 'get',
            'streaming' => false,
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
     * @return \Twient\Auth\OAuth
     */
    public function oAuth($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
    {
        $this->auth = new OAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
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
     * @param string $methodName        method name (ie. statuses/sample)
     * @param array $params             key-value parameter for method
     * @param mixed $callback           callback function (ie. "function_name" or array(class, "method_name") or closure)
     * @return mixed
     * @throws Exception
     */
    public function streaming($methodName, array $params = array(), $callback = null)
    {
        if (!array_key_exists($methodName, $this->streamingApis)) {
            throw new Exception('This method is not supported: ' . $methodName);
        }
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

    // via API v1.1

    public function getStatusesMentionsTimeline($params = array())
    {
        return $this->get('statuses/mentions_timeline', $params);
    }

    public function getStatusesUserTimeline($params = array())
    {
        return $this->get('statuses/user_timeline', $params);
    }

    public function getStatusesHomeTimeline($params = array())
    {
        return $this->get('statuses/home_timeline', $params);
    }

    public function getStatusesRetweetsOfMe($params = array())
    {
        return $this->get('statuses/retweets_of_me', $params);
    }

    public function getStatusesRetweetsId($params = array())
    {
        return $this->get('statuses/retweets/:id', $params);
    }

    public function getStatusesShowId($params = array())
    {
        return $this->get('statuses/show/:id', $params);
    }

    public function postStatusesDestroyId($params = array())
    {
        return $this->post('statuses/destroy/:id', $params);
    }

    public function postStatusesUpdate($params = array())
    {
        return $this->post('statuses/update', $params);
    }

    public function postStatusesRetweetId($params = array())
    {
        return $this->post('statuses/retweet/:id', $params);
    }

    public function postStatusesUpdateWithMedia($params = array())
    {
        return $this->post('statuses/update_with_media', $params);
    }

    public function getStatusesOembed($params = array())
    {
        return $this->get('statuses/oembed', $params);
    }

    public function getSearchTweets($params = array())
    {
        return $this->get('search/tweets', $params);
    }

    public function streamingStatusesFilter($params = array(), $callback)
    {
        return $this->streaming('statuses/filter', $params, $callback);
    }

    public function streamingStatusesSample($params = array(), $callback)
    {
        return $this->streaming('statuses/sample', $params, $callback);
    }

    public function streamingStatusesFirehose($params = array(), $callback)
    {
        return $this->streaming('statuses/firehose', $params, $callback);
    }

    public function streamingUser($params = array(), $callback)
    {
        return $this->streaming('user', $params, $callback);
    }

    public function streamingSite($params = array(), $callback)
    {
        return $this->streaming('site', $params, $callback);
    }

    public function getDirectMessages($params = array())
    {
        return $this->get('direct_messages', $params);
    }

    public function getDirectMessagesSent($params = array())
    {
        return $this->get('direct_messages/sent', $params);
    }

    public function getDirectMessagesShow($params = array())
    {
        return $this->get('direct_messages/show', $params);
    }

    public function postDirectMessagesDestroy($params = array())
    {
        return $this->post('direct_messages/destroy', $params);
    }

    public function postDirectMessagesNew($params = array())
    {
        return $this->post('direct_messages/new', $params);
    }

    public function getFriendshipsNoRetweetsIds($params = array())
    {
        return $this->get('friendships/no_retweets/ids', $params);
    }

    public function getFriendsIds($params = array())
    {
        return $this->get('friends/ids', $params);
    }

    public function getFollowersIds($params = array())
    {
        return $this->get('followers/ids', $params);
    }

    public function getFriendshipsLookup($params = array())
    {
        return $this->get('friendships/lookup', $params);
    }

    public function getFriendshipsIncoming($params = array())
    {
        return $this->get('friendships/incoming', $params);
    }

    public function getFriendshipsOutgoing($params = array())
    {
        return $this->get('friendships/outgoing', $params);
    }

    public function postFriendshipsCreate($params = array())
    {
        return $this->post('friendships/create', $params);
    }

    public function postFriendshipsDestroy($params = array())
    {
        return $this->post('friendships/destroy', $params);
    }

    public function postFriendshipsUpdate($params = array())
    {
        return $this->post('friendships/update', $params);
    }

    public function getFriendshipsShow($params = array())
    {
        return $this->get('friendships/show', $params);
    }

    public function getFriendsList($params = array())
    {
        return $this->get('friends/list', $params);
    }

    public function getFollowersList($params = array())
    {
        return $this->get('followers/list', $params);
    }

    public function getAccountSettings($params = array())
    {
        return $this->get('account/settings', $params);
    }

    public function getAccountVerifyCredentials($params = array())
    {
        return $this->get('account/verify_credentials', $params);
    }

    public function postAccountSettings($params = array())
    {
        return $this->post('account/settings', $params);
    }

    public function postAccountUpdateDeliveryDevice($params = array())
    {
        return $this->post('account/update_delivery_device', $params);
    }

    public function postAccountUpdateProfile($params = array())
    {
        return $this->post('account/update_profile', $params);
    }

    public function postAccountUpdateProfileBackgroundImage($params = array())
    {
        return $this->post('account/update_profile_background_image', $params);
    }

    public function postAccountUpdateProfileColors($params = array())
    {
        return $this->post('account/update_profile_colors', $params);
    }

    public function postAccountUpdateProfileImage($params = array())
    {
        return $this->post('account/update_profile_image', $params);
    }

    public function getBlocksList($params = array())
    {
        return $this->get('blocks/list', $params);
    }

    public function getBlocksIds($params = array())
    {
        return $this->get('blocks/ids', $params);
    }

    public function postBlocksCreate($params = array())
    {
        return $this->post('blocks/create', $params);
    }

    public function postBlocksDestroy($params = array())
    {
        return $this->post('blocks/destroy', $params);
    }

    public function getUsersLookup($params = array())
    {
        return $this->get('users/lookup', $params);
    }

    public function getUsersShow($params = array())
    {
        return $this->get('users/show', $params);
    }

    public function getUsersSearch($params = array())
    {
        return $this->get('users/search', $params);
    }

    public function getUsersContributees($params = array())
    {
        return $this->get('users/contributees', $params);
    }

    public function getUsersContributors($params = array())
    {
        return $this->get('users/contributors', $params);
    }

    public function postAccountRemoveProfileBanner($params = array())
    {
        return $this->post('account/remove_profile_banner', $params);
    }

    public function postAccountUpdateProfileBanner($params = array())
    {
        return $this->post('account/update_profile_banner', $params);
    }

    public function getUsersProfileBanner($params = array())
    {
        return $this->get('users/profile_banner', $params);
    }

    public function getUsersSuggestionsSlug($params = array())
    {
        return $this->get('users/suggestions/:slug', $params);
    }

    public function getUsersSuggestions($params = array())
    {
        return $this->get('users/suggestions', $params);
    }

    public function getUsersSuggestionsSlugMembers($params = array())
    {
        return $this->get('users/suggestions/:slug/members', $params);
    }

    public function getFavoritesList($params = array())
    {
        return $this->get('favorites/list', $params);
    }

    public function postFavoritesDestroy($params = array())
    {
        return $this->post('favorites/destroy', $params);
    }

    public function postFavoritesCreate($params = array())
    {
        return $this->post('favorites/create', $params);
    }

    public function getListsList($params = array())
    {
        return $this->get('lists/list', $params);
    }

    public function getListsStatuses($params = array())
    {
        return $this->get('lists/statuses', $params);
    }

    public function postListsMembersDestroy($params = array())
    {
        return $this->post('lists/members/destroy', $params);
    }

    public function getListsMemberships($params = array())
    {
        return $this->get('lists/memberships', $params);
    }

    public function getListsSubscribers($params = array())
    {
        return $this->get('lists/subscribers', $params);
    }

    public function postListsSubscribersCreate($params = array())
    {
        return $this->post('lists/subscribers/create', $params);
    }

    public function getListsSubscribersShow($params = array())
    {
        return $this->get('lists/subscribers/show', $params);
    }

    public function postListsSubscribersDestroy($params = array())
    {
        return $this->post('lists/subscribers/destroy', $params);
    }

    public function postListsMembersCreateAll($params = array())
    {
        return $this->post('lists/members/create_all', $params);
    }

    public function getListsMembersShow($params = array())
    {
        return $this->get('lists/members/show', $params);
    }

    public function getListsMembers($params = array())
    {
        return $this->get('lists/members', $params);
    }

    public function postListsMembersCreate($params = array())
    {
        return $this->post('lists/members/create', $params);
    }

    public function postListsDestroy($params = array())
    {
        return $this->post('lists/destroy', $params);
    }

    public function postListsUpdate($params = array())
    {
        return $this->post('lists/update', $params);
    }

    public function postListsCreate($params = array())
    {
        return $this->post('lists/create', $params);
    }

    public function getListsShow($params = array())
    {
        return $this->get('lists/show', $params);
    }

    public function getListsSubscriptions($params = array())
    {
        return $this->get('lists/subscriptions', $params);
    }

    public function postListsMembersDestroyAll($params = array())
    {
        return $this->post('lists/members/destroy_all', $params);
    }

    public function getSavedSearchesList($params = array())
    {
        return $this->get('saved_searches/list', $params);
    }

    public function getSavedSearchesShowId($params = array())
    {
        return $this->get('saved_searches/show/:id', $params);
    }

    public function postSavedSearchesCreate($params = array())
    {
        return $this->post('saved_searches/create', $params);
    }

    public function postSavedSearchesDestroyId($params = array())
    {
        return $this->post('saved_searches/destroy/:id', $params);
    }

    public function getGeoIdPlaceId($params = array())
    {
        return $this->get('geo/id/:place_id', $params);
    }

    public function getGeoReverseGeocode($params = array())
    {
        return $this->get('geo/reverse_geocode', $params);
    }

    public function getGeoSearch($params = array())
    {
        return $this->get('geo/search', $params);
    }

    public function getGeoSimilarPlaces($params = array())
    {
        return $this->get('geo/similar_places', $params);
    }

    public function postGeoPlace($params = array())
    {
        return $this->post('geo/place', $params);
    }

    public function getTrendsPlace($params = array())
    {
        return $this->get('trends/place', $params);
    }

    public function getTrendsAvailable($params = array())
    {
        return $this->get('trends/available', $params);
    }

    public function getTrendsClosest($params = array())
    {
        return $this->get('trends/closest', $params);
    }

    public function postUsersReportSpam($params = array())
    {
        return $this->post('users/report_spam', $params);
    }

    public function getHelpConfiguration($params = array())
    {
        return $this->get('help/configuration', $params);
    }

    public function getHelpLanguages($params = array())
    {
        return $this->get('help/languages', $params);
    }

    public function getHelpPrivacy($params = array())
    {
        return $this->get('help/privacy', $params);
    }

    public function getHelpTos($params = array())
    {
        return $this->get('help/tos', $params);
    }

    public function getApplicationRateLimitStatus($params = array())
    {
        return $this->get('application/rate_limit_status', $params);
    }
}
