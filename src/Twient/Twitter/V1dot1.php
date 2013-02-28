<?php
/**
 * Twient\Exception class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Twient\Twitter;

class V1dot1 extends \Twient\Twitter
{
    public function statusesMentionsTimeline($params = array())
    {
        return $this->get('statuses/mentions_timeline', $params);
    }

    public function statusesUserTimeline($params = array())
    {
        return $this->get('statuses/user_timeline', $params);
    }

    public function statusesHomeTimeline($params = array())
    {
        return $this->get('statuses/home_timeline', $params);
    }

    public function statusesRetweetsOfMe($params = array())
    {
        return $this->get('statuses/retweets_of_me', $params);
    }

    public function statusesRetweetsId($params = array())
    {
        return $this->get('statuses/retweets/:id', $params);
    }

    public function statusesShowId($params = array())
    {
        return $this->get('statuses/show/:id', $params);
    }

    public function statusesDestroyId($params = array())
    {
        return $this->post('statuses/destroy/:id', $params);
    }

    public function statusesUpdate($params = array())
    {
        return $this->post('statuses/update', $params);
    }

    public function statusesRetweetId($params = array())
    {
        return $this->post('statuses/retweet/:id', $params);
    }

    public function statusesUpdateWithMedia($params = array())
    {
        return $this->post('statuses/update_with_media', $params);
    }

    public function statusesOembed($params = array())
    {
        return $this->get('statuses/oembed', $params);
    }

    public function searchTweets($params = array())
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

    public function directMessages($params = array())
    {
        return $this->get('direct_messages', $params);
    }

    public function directMessagesSent($params = array())
    {
        return $this->get('direct_messages/sent', $params);
    }

    public function directMessagesShow($params = array())
    {
        return $this->get('direct_messages/show', $params);
    }

    public function directMessagesDestroy($params = array())
    {
        return $this->post('direct_messages/destroy', $params);
    }

    public function directMessagesNew($params = array())
    {
        return $this->post('direct_messages/new', $params);
    }

    public function friendshipsNoRetweetsIds($params = array())
    {
        return $this->get('friendships/no_retweets/ids', $params);
    }

    public function friendsIds($params = array())
    {
        return $this->get('friends/ids', $params);
    }

    public function followersIds($params = array())
    {
        return $this->get('followers/ids', $params);
    }

    public function friendshipsLookup($params = array())
    {
        return $this->get('friendships/lookup', $params);
    }

    public function friendshipsIncoming($params = array())
    {
        return $this->get('friendships/incoming', $params);
    }

    public function friendshipsOutgoing($params = array())
    {
        return $this->get('friendships/outgoing', $params);
    }

    public function friendshipsCreate($params = array())
    {
        return $this->post('friendships/create', $params);
    }

    public function friendshipsDestroy($params = array())
    {
        return $this->post('friendships/destroy', $params);
    }

    public function friendshipsUpdate($params = array())
    {
        return $this->post('friendships/update', $params);
    }

    public function friendshipsShow($params = array())
    {
        return $this->get('friendships/show', $params);
    }

    public function friendsList($params = array())
    {
        return $this->get('friends/list', $params);
    }

    public function followersList($params = array())
    {
        return $this->get('followers/list', $params);
    }

    public function accountSettings($params = array())
    {
        if (empty($params)) {
            return $this->get('account/settings', $params);
        } else {
            return $this->post('account/settings', $params);
        }
    }

    public function accountVerifyCredentials($params = array())
    {
        return $this->get('account/verify_credentials', $params);
    }

    public function accountUpdateDeliveryDevice($params = array())
    {
        return $this->post('account/update_delivery_device', $params);
    }

    public function accountUpdateProfile($params = array())
    {
        return $this->post('account/update_profile', $params);
    }

    public function accountUpdateProfileBackgroundImage($params = array())
    {
        return $this->post('account/update_profile_background_image', $params);
    }

    public function accountUpdateProfileColors($params = array())
    {
        return $this->post('account/update_profile_colors', $params);
    }

    public function accountUpdateProfileImage($params = array())
    {
        return $this->post('account/update_profile_image', $params);
    }

    public function blocksList($params = array())
    {
        return $this->get('blocks/list', $params);
    }

    public function blocksIds($params = array())
    {
        return $this->get('blocks/ids', $params);
    }

    public function blocksCreate($params = array())
    {
        return $this->post('blocks/create', $params);
    }

    public function blocksDestroy($params = array())
    {
        return $this->post('blocks/destroy', $params);
    }

    public function usersLookup($params = array())
    {
        return $this->get('users/lookup', $params);
    }

    public function usersShow($params = array())
    {
        return $this->get('users/show', $params);
    }

    public function usersSearch($params = array())
    {
        return $this->get('users/search', $params);
    }

    public function usersContributees($params = array())
    {
        return $this->get('users/contributees', $params);
    }

    public function usersContributors($params = array())
    {
        return $this->get('users/contributors', $params);
    }

    public function accountRemoveProfileBanner($params = array())
    {
        return $this->post('account/remove_profile_banner', $params);
    }

    public function accountUpdateProfileBanner($params = array())
    {
        return $this->post('account/update_profile_banner', $params);
    }

    public function usersProfileBanner($params = array())
    {
        return $this->get('users/profile_banner', $params);
    }

    public function usersSuggestionsSlug($params = array())
    {
        return $this->get('users/suggestions/:slug', $params);
    }

    public function usersSuggestions($params = array())
    {
        return $this->get('users/suggestions', $params);
    }

    public function usersSuggestionsSlugMembers($params = array())
    {
        return $this->get('users/suggestions/:slug/members', $params);
    }

    public function favoritesList($params = array())
    {
        return $this->get('favorites/list', $params);
    }

    public function favoritesDestroy($params = array())
    {
        return $this->post('favorites/destroy', $params);
    }

    public function favoritesCreate($params = array())
    {
        return $this->post('favorites/create', $params);
    }

    public function listsList($params = array())
    {
        return $this->get('lists/list', $params);
    }

    public function listsStatuses($params = array())
    {
        return $this->get('lists/statuses', $params);
    }

    public function listsMembersDestroy($params = array())
    {
        return $this->post('lists/members/destroy', $params);
    }

    public function listsMemberships($params = array())
    {
        return $this->get('lists/memberships', $params);
    }

    public function listsSubscribers($params = array())
    {
        return $this->get('lists/subscribers', $params);
    }

    public function listsSubscribersCreate($params = array())
    {
        return $this->post('lists/subscribers/create', $params);
    }

    public function listsSubscribersShow($params = array())
    {
        return $this->get('lists/subscribers/show', $params);
    }

    public function listsSubscribersDestroy($params = array())
    {
        return $this->post('lists/subscribers/destroy', $params);
    }

    public function listsMembersCreateAll($params = array())
    {
        return $this->post('lists/members/create_all', $params);
    }

    public function listsMembersShow($params = array())
    {
        return $this->get('lists/members/show', $params);
    }

    public function listsMembers($params = array())
    {
        return $this->get('lists/members', $params);
    }

    public function listsMembersCreate($params = array())
    {
        return $this->post('lists/members/create', $params);
    }

    public function listsDestroy($params = array())
    {
        return $this->post('lists/destroy', $params);
    }

    public function listsUpdate($params = array())
    {
        return $this->post('lists/update', $params);
    }

    public function listsCreate($params = array())
    {
        return $this->post('lists/create', $params);
    }

    public function listsShow($params = array())
    {
        return $this->get('lists/show', $params);
    }

    public function listsSubscriptions($params = array())
    {
        return $this->get('lists/subscriptions', $params);
    }

    public function listsMembersDestroyAll($params = array())
    {
        return $this->post('lists/members/destroy_all', $params);
    }

    public function savedSearchesList($params = array())
    {
        return $this->get('saved_searches/list', $params);
    }

    public function savedSearchesShowId($params = array())
    {
        return $this->get('saved_searches/show/:id', $params);
    }

    public function savedSearchesCreate($params = array())
    {
        return $this->post('saved_searches/create', $params);
    }

    public function savedSearchesDestroyId($params = array())
    {
        return $this->post('saved_searches/destroy/:id', $params);
    }

    public function geoIdPlaceId($params = array())
    {
        return $this->get('geo/id/:place_id', $params);
    }

    public function geoReverseGeocode($params = array())
    {
        return $this->get('geo/reverse_geocode', $params);
    }

    public function geoSearch($params = array())
    {
        return $this->get('geo/search', $params);
    }

    public function geoSimilarPlaces($params = array())
    {
        return $this->get('geo/similar_places', $params);
    }

    public function geoPlace($params = array())
    {
        return $this->post('geo/place', $params);
    }

    public function trendsPlace($params = array())
    {
        return $this->get('trends/place', $params);
    }

    public function trendsAvailable($params = array())
    {
        return $this->get('trends/available', $params);
    }

    public function trendsClosest($params = array())
    {
        return $this->get('trends/closest', $params);
    }

    public function usersReportSpam($params = array())
    {
        return $this->post('users/report_spam', $params);
    }

    public function helpConfiguration($params = array())
    {
        return $this->get('help/configuration', $params);
    }

    public function helpLanguages($params = array())
    {
        return $this->get('help/languages', $params);
    }

    public function helpPrivacy($params = array())
    {
        return $this->get('help/privacy', $params);
    }

    public function helpTos($params = array())
    {
        return $this->get('help/tos', $params);
    }

    public function applicationRateLimitStatus($params = array())
    {
        return $this->get('application/rate_limit_status', $params);
    }
}
