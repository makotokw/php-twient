<?php
/**
 * Twient\Twitter\V1dot1 class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Twient\Twitter;

class V1dot1 extends \Twient\Twitter
{
    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/mentions_timeline
     */
    public function statusesMentionsTimeline($params = array())
    {
        return $this->get('statuses/mentions_timeline', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
     */
    public function statusesUserTimeline($params = array())
    {
        return $this->get('statuses/user_timeline', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/home_timeline
     */
    public function statusesHomeTimeline($params = array())
    {
        return $this->get('statuses/home_timeline', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/retweets_of_me
     */
    public function statusesRetweetsOfMe($params = array())
    {
        return $this->get('statuses/retweets_of_me', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/retweets/%3Aid
     */
    public function statusesRetweetsId($params = array())
    {
        return $this->get('statuses/retweets/:id', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/show/%3Aid
     */
    public function statusesShowId($params = array())
    {
        return $this->get('statuses/show/:id', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/destroy/%3Aid
     */
    public function statusesDestroyId($params = array())
    {
        return $this->post('statuses/destroy/:id', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/update
     */
    public function statusesUpdate($params = array())
    {
        return $this->post('statuses/update', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/retweet/%3Aid
     */
    public function statusesRetweetId($params = array())
    {
        return $this->post('statuses/retweet/:id', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/update_with_media
     */
    public function statusesUpdateWithMedia($params = array())
    {
        return $this->post('statuses/update_with_media', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/oembed
     */
    public function statusesOembed($params = array())
    {
        return $this->get('statuses/oembed', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/retweeters/ids
     */
    public function statusesRetweetersIds($params = array())
    {
        return $this->get('statuses/retweeters/ids', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/search/tweets
     */
    public function searchTweets($params = array())
    {
        return $this->get('search/tweets', $params);
    }

    /**
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/filter
     */
    public function streamingStatusesFilter($params = array(), $callback)
    {
        return $this->streaming('statuses/filter', $params, $callback);
    }

    /**
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/sample
     */
    public function streamingStatusesSample($params = array(), $callback)
    {
        return $this->streaming('statuses/sample', $params, $callback);
    }

    /**
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/firehose
     */
    public function streamingStatusesFirehose($params = array(), $callback)
    {
        return $this->streaming('statuses/firehose', $params, $callback);
    }

    /**
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/user
     */
    public function streamingUser($params = array(), $callback)
    {
        return $this->streaming('user', $params, $callback);
    }

    /**
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/site
     */
    public function streamingSite($params = array(), $callback)
    {
        return $this->streaming('site', $params, $callback);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/direct_messages
     */
    public function directMessages($params = array())
    {
        return $this->get('direct_messages', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/direct_messages/sent
     */
    public function directMessagesSent($params = array())
    {
        return $this->get('direct_messages/sent', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/direct_messages/show
     */
    public function directMessagesShow($params = array())
    {
        return $this->get('direct_messages/show', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/direct_messages/destroy
     */
    public function directMessagesDestroy($params = array())
    {
        return $this->post('direct_messages/destroy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/direct_messages/new
     */
    public function directMessagesNew($params = array())
    {
        return $this->post('direct_messages/new', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/no_retweets/ids
     */
    public function friendshipsNoRetweetsIds($params = array())
    {
        return $this->get('friendships/no_retweets/ids', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friends/ids
     */
    public function friendsIds($params = array())
    {
        return $this->get('friends/ids', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/followers/ids
     */
    public function followersIds($params = array())
    {
        return $this->get('followers/ids', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/lookup
     */
    public function friendshipsLookup($params = array())
    {
        return $this->get('friendships/lookup', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/incoming
     */
    public function friendshipsIncoming($params = array())
    {
        return $this->get('friendships/incoming', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/outgoing
     */
    public function friendshipsOutgoing($params = array())
    {
        return $this->get('friendships/outgoing', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/friendships/create
     */
    public function friendshipsCreate($params = array())
    {
        return $this->post('friendships/create', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/friendships/destroy
     */
    public function friendshipsDestroy($params = array())
    {
        return $this->post('friendships/destroy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/friendships/update
     */
    public function friendshipsUpdate($params = array())
    {
        return $this->post('friendships/update', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/show
     */
    public function friendshipsShow($params = array())
    {
        return $this->get('friendships/show', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friends/list
     */
    public function friendsList($params = array())
    {
        return $this->get('friends/list', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/followers/list
     */
    public function followersList($params = array())
    {
        return $this->get('followers/list', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/account/settings
     * @see https://dev.twitter.com/docs/api/1.1/post/account/settings
     */
    public function accountSettings($params = array())
    {
        if (empty($params)) {
            return $this->get('account/settings', $params);
        } else {
            return $this->post('account/settings', $params);
        }
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/account/verify_credentials
     */
    public function accountVerifyCredentials($params = array())
    {
        return $this->get('account/verify_credentials', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_delivery_device
     */
    public function accountUpdateDeliveryDevice($params = array())
    {
        return $this->post('account/update_delivery_device', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile
     */
    public function accountUpdateProfile($params = array())
    {
        return $this->post('account/update_profile', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_background_image
     */
    public function accountUpdateProfileBackgroundImage($params = array())
    {
        return $this->post('account/update_profile_background_image', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_colors
     */
    public function accountUpdateProfileColors($params = array())
    {
        return $this->post('account/update_profile_colors', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_image
     */
    public function accountUpdateProfileImage($params = array())
    {
        return $this->post('account/update_profile_image', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/blocks/list
     */
    public function blocksList($params = array())
    {
        return $this->get('blocks/list', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/blocks/ids
     */
    public function blocksIds($params = array())
    {
        return $this->get('blocks/ids', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/blocks/create
     */
    public function blocksCreate($params = array())
    {
        return $this->post('blocks/create', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/blocks/destroy
     */
    public function blocksDestroy($params = array())
    {
        return $this->post('blocks/destroy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/lookup
     */
    public function usersLookup($params = array())
    {
        return $this->get('users/lookup', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/show
     */
    public function usersShow($params = array())
    {
        return $this->get('users/show', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/search
     */
    public function usersSearch($params = array())
    {
        return $this->get('users/search', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/contributees
     */
    public function usersContributees($params = array())
    {
        return $this->get('users/contributees', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/contributors
     */
    public function usersContributors($params = array())
    {
        return $this->get('users/contributors', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/remove_profile_banner
     */
    public function accountRemoveProfileBanner($params = array())
    {
        return $this->post('account/remove_profile_banner', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_banner
     */
    public function accountUpdateProfileBanner($params = array())
    {
        return $this->post('account/update_profile_banner', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/profile_banner
     */
    public function usersProfileBanner($params = array())
    {
        return $this->get('users/profile_banner', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/suggestions/%3Aslug
     */
    public function usersSuggestionsSlug($params = array())
    {
        return $this->get('users/suggestions/:slug', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/suggestions
     */
    public function usersSuggestions($params = array())
    {
        return $this->get('users/suggestions', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/suggestions/%3Aslug/members
     */
    public function usersSuggestionsSlugMembers($params = array())
    {
        return $this->get('users/suggestions/:slug/members', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/favorites/list
     */
    public function favoritesList($params = array())
    {
        return $this->get('favorites/list', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/favorites/destroy
     */
    public function favoritesDestroy($params = array())
    {
        return $this->post('favorites/destroy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/favorites/create
     */
    public function favoritesCreate($params = array())
    {
        return $this->post('favorites/create', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/list
     */
    public function listsList($params = array())
    {
        return $this->get('lists/list', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/statuses
     */
    public function listsStatuses($params = array())
    {
        return $this->get('lists/statuses', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/destroy
     */
    public function listsMembersDestroy($params = array())
    {
        return $this->post('lists/members/destroy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/memberships
     */
    public function listsMemberships($params = array())
    {
        return $this->get('lists/memberships', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/subscribers
     */
    public function listsSubscribers($params = array())
    {
        return $this->get('lists/subscribers', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/subscribers/create
     */
    public function listsSubscribersCreate($params = array())
    {
        return $this->post('lists/subscribers/create', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/subscribers/show
     */
    public function listsSubscribersShow($params = array())
    {
        return $this->get('lists/subscribers/show', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/subscribers/destroy
     */
    public function listsSubscribersDestroy($params = array())
    {
        return $this->post('lists/subscribers/destroy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/create_all
     */
    public function listsMembersCreateAll($params = array())
    {
        return $this->post('lists/members/create_all', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/members/show
     */
    public function listsMembersShow($params = array())
    {
        return $this->get('lists/members/show', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/members
     */
    public function listsMembers($params = array())
    {
        return $this->get('lists/members', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/create
     */
    public function listsMembersCreate($params = array())
    {
        return $this->post('lists/members/create', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/destroy
     */
    public function listsDestroy($params = array())
    {
        return $this->post('lists/destroy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/update
     */
    public function listsUpdate($params = array())
    {
        return $this->post('lists/update', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/create
     */
    public function listsCreate($params = array())
    {
        return $this->post('lists/create', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/show
     */
    public function listsShow($params = array())
    {
        return $this->get('lists/show', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/subscriptions
     */
    public function listsSubscriptions($params = array())
    {
        return $this->get('lists/subscriptions', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/destroy_all
     */
    public function listsMembersDestroyAll($params = array())
    {
        return $this->post('lists/members/destroy_all', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/ownerships
     */
    public function listsOwnerships($params = array())
    {
        return $this->get('lists/ownerships', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/saved_searches/list
     */
    public function savedSearchesList($params = array())
    {
        return $this->get('saved_searches/list', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/saved_searches/show/%3Aid
     */
    public function savedSearchesShowId($params = array())
    {
        return $this->get('saved_searches/show/:id', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/saved_searches/create
     */
    public function savedSearchesCreate($params = array())
    {
        return $this->post('saved_searches/create', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/saved_searches/destroy/%3Aid
     */
    public function savedSearchesDestroyId($params = array())
    {
        return $this->post('saved_searches/destroy/:id', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/id/%3Aplace_id
     */
    public function geoIdPlaceId($params = array())
    {
        return $this->get('geo/id/:place_id', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/reverse_geocode
     */
    public function geoReverseGeocode($params = array())
    {
        return $this->get('geo/reverse_geocode', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/search
     */
    public function geoSearch($params = array())
    {
        return $this->get('geo/search', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/similar_places
     */
    public function geoSimilarPlaces($params = array())
    {
        return $this->get('geo/similar_places', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/geo/place
     */
    public function geoPlace($params = array())
    {
        return $this->post('geo/place', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/trends/place
     */
    public function trendsPlace($params = array())
    {
        return $this->get('trends/place', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/trends/available
     */
    public function trendsAvailable($params = array())
    {
        return $this->get('trends/available', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/trends/closest
     */
    public function trendsClosest($params = array())
    {
        return $this->get('trends/closest', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/users/report_spam
     */
    public function usersReportSpam($params = array())
    {
        return $this->post('users/report_spam', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/configuration
     */
    public function helpConfiguration($params = array())
    {
        return $this->get('help/configuration', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/languages
     */
    public function helpLanguages($params = array())
    {
        return $this->get('help/languages', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/privacy
     */
    public function helpPrivacy($params = array())
    {
        return $this->get('help/privacy', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/tos
     */
    public function helpTos($params = array())
    {
        return $this->get('help/tos', $params);
    }

    /**
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/application/rate_limit_status
     */
    public function applicationRateLimitStatus($params = array())
    {
        return $this->get('application/rate_limit_status', $params);
    }
}
