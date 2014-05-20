<?php
/**
 * Twitter\V1dot1 class
 * This file is part of the Makotokw\Twient package.
 *
 * @author     Makoto Kawasaki <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Makotokw\Twient\Twitter;

use \Makotokw\Twient\Twitter as Client;

class V1dot1 extends Client
{

    /**
     * statuses/mentions_timeline
     *
     * Returns the 20 most recent mentions (tweets containing a users's @screen_name) for
     * the authenticating user. The timeline returned is the equivalent of the one seen
     * when you view your mentions on twitter.com. This method can only return up to 800
     * tweets. See Working with Timelines for...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/mentions_timeline
     */
    public function statusesMentionsTimeline($params = array())
    {
        return $this->get('statuses/mentions_timeline', $params);
    }

    /**
     * statuses/user_timeline
     *
     * Returns a collection of the most recent Tweets posted by the user indicated by the
     * screen_name or user_id parameters. User timelines belonging to protected users may
     * only be requested when the authenticated user either "owns" the timeline or is an
     * approved follower of the owner. The timeline...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
     */
    public function statusesUserTimeline($params = array())
    {
        return $this->get('statuses/user_timeline', $params);
    }

    /**
     * statuses/home_timeline
     *
     * Returns a collection of the most recent Tweets and retweets posted by the authenticating
     * user and the users they follow. The home timeline is central to how most users interact
     * with the Twitter service. Up to 800 Tweets are obtainable on the home timeline. It
     * is more volatile for users that follow...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/home_timeline
     */
    public function statusesHomeTimeline($params = array())
    {
        return $this->get('statuses/home_timeline', $params);
    }

    /**
     * statuses/retweets_of_me
     *
     * Returns the most recent tweets authored by the authenticating user that have been
     * retweeted by others. This timeline is a subset of the user's GET statuses/user_timeline.
     * See Working with Timelines for instructions on traversing timelines.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/retweets_of_me
     */
    public function statusesRetweetsOfMe($params = array())
    {
        return $this->get('statuses/retweets_of_me', $params);
    }

    /**
     * statuses/retweets/:id
     *
     * Returns a collection of the 100 most recent retweets of the tweet specified by the
     * id parameter.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/retweets/%3Aid
     */
    public function statusesRetweetsId($params = array())
    {
        return $this->get('statuses/retweets/:id', $params);
    }

    /**
     * statuses/show/:id
     *
     * Returns a single Tweet, specified by the id parameter. The Tweet's author will also
     * be embedded within the tweet. See GET statuses/lookup for getting Tweets in bulk
     * (up to 100 per call). See also Embeddable Timelines, Embeddable Tweets, and GET statuses/oembed
     * for tools to render Tweets according...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/show/%3Aid
     */
    public function statusesShowId($params = array())
    {
        return $this->get('statuses/show/:id', $params);
    }

    /**
     * statuses/destroy/:id
     *
     * Destroys the status specified by the required ID parameter. The authenticating user
     * must be the author of the specified status. Returns the destroyed status if successful.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/destroy/%3Aid
     */
    public function statusesDestroyId($params = array())
    {
        return $this->post('statuses/destroy/:id', $params);
    }

    /**
     * statuses/update
     *
     * Updates the authenticating user's current status, also known as tweeting. To upload
     * an image to accompany the tweet, use POST statuses/update_with_media. For each update
     * attempt, the update text is compared with the authenticating user's recent tweets.
     * Any attempt that would result in duplication...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/update
     */
    public function statusesUpdate($params = array())
    {
        return $this->post('statuses/update', $params);
    }

    /**
     * statuses/retweet/:id
     *
     * Retweets a tweet. Returns the original tweet with retweet details embedded.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/retweet/%3Aid
     */
    public function statusesRetweetId($params = array())
    {
        return $this->post('statuses/retweet/:id', $params);
    }

    /**
     * statuses/update_with_media
     *
     * Updates the authenticating user's current status and attaches media for upload. In
     * other words, it creates a Tweet with a picture attached. Unlike POST statuses/update,
     * this method expects raw multipart data. Your POST request's Content-Type should be
     * set to multipart/form-data with the media[]...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/update_with_media
     */
    public function statusesUpdateWithMedia($params = array())
    {
        return $this->post('statuses/update_with_media', $params);
    }

    /**
     * statuses/oembed
     *
     * Returns information allowing the creation of an embedded representation of a Tweet
     * on third party sites.  See the oEmbed specification for information about the response
     * format. While this endpoint allows a bit of customization for the final appearance
     * of the embedded Tweet, be aware that the...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/oembed
     */
    public function statusesOembed($params = array())
    {
        return $this->get('statuses/oembed', $params);
    }

    /**
     * statuses/retweeters/ids
     *
     * Returns a collection of up to 100 user IDs belonging to users who have retweeted
     * the tweet specified by the id parameter. This method offers similar data to GET statuses/retweets/:id
     * and replaces API v1's GET statuses/:id/retweeted_by/ids method.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/retweeters/ids
     */
    public function statusesRetweetersIds($params = array())
    {
        return $this->get('statuses/retweeters/ids', $params);
    }

    /**
     * search/tweets
     *
     * Returns a collection of relevant Tweets matching a specified query. Please note that
     * Twitter's search service and, by extension, the Search API is not meant to be an
     * exhaustive source of Tweets. Not all Tweets will be indexed or made available via
     * the search interface. In API v1.1, the response...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/search/tweets
     */
    public function searchTweets($params = array())
    {
        return $this->get('search/tweets', $params);
    }

    /**
     * statuses/filter
     *
     * Returns public statuses that match one or more filter predicates. Multiple parameters
     * may be specified which allows most clients to use a single connection to the Streaming
     * API.  Both GET and POST requests are supported, but GET requests with too many parameters
     * may cause the request to be...
     *
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/statuses/filter
     */
    public function streamingStatusesFilter($params = array(), $callback = null)
    {
        return $this->streaming('statuses/filter', $params, $callback);
    }

    /**
     * statuses/sample
     *
     * Returns a small random sample of all public statuses.  The Tweets returned by the
     * default access level are the same, so if two different clients connect to this endpoint,
     * they will see the same Tweets.
     *
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/sample
     */
    public function streamingStatusesSample($params = array(), $callback = null)
    {
        return $this->streaming('statuses/sample', $params, $callback);
    }

    /**
     * statuses/firehose
     *
     * This endpoint requires special permission to access. Returns all public statuses.
     * Few applications require this level of access. Creative use of a combination of other
     * resources and various access levels can satisfy nearly every application use case.
     *
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/firehose
     */
    public function streamingStatusesFirehose($params = array(), $callback = null)
    {
        return $this->streaming('statuses/firehose', $params, $callback);
    }

    /**
     * user
     *
     * Streams messages for a single user, as described in User streams.
     *
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/user
     */
    public function streamingUser($params = array(), $callback = null)
    {
        return $this->streaming('user', $params, $callback);
    }

    /**
     * site
     *
     * Streams messages for a set of users, as described in Site streams.
     *
     * @param array $params
     * @param $callback
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/site
     */
    public function streamingSite($params = array(), $callback = null)
    {
        return $this->streaming('site', $params, $callback);
    }

    /**
     * direct_messages
     *
     * Returns the 20 most recent direct messages sent to the authenticating user. Includes
     * detailed information about the sender and recipient user. You can request up to 200
     * direct messages per call, up to a maximum of 800 incoming DMs. Important: This method
     * requires an access token with RWD (read,...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/direct_messages
     */
    public function directMessages($params = array())
    {
        return $this->get('direct_messages', $params);
    }

    /**
     * direct_messages/sent
     *
     * Returns the 20 most recent direct messages sent by the authenticating user. Includes
     * detailed information about the sender and recipient user. You can request up to 200
     * direct messages per call, up to a maximum of 800 outgoing DMs. Important: This method
     * requires an access token with RWD (read,...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/direct_messages/sent
     */
    public function directMessagesSent($params = array())
    {
        return $this->get('direct_messages/sent', $params);
    }

    /**
     * direct_messages/show
     *
     * Returns a single direct message, specified by an id parameter. Like the /1.1/direct_messages.format
     * request, this method will include the user objects of the sender and recipient. Important:
     * This method requires an access token with RWD (read, write...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/direct_messages/show
     */
    public function directMessagesShow($params = array())
    {
        return $this->get('direct_messages/show', $params);
    }

    /**
     * direct_messages/destroy
     *
     * Destroys the direct message specified in the required ID parameter. The authenticating
     * user must be the recipient of the specified direct message. Important: This method
     * requires an access token with RWD (read, write...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/direct_messages/destroy
     */
    public function directMessagesDestroy($params = array())
    {
        return $this->post('direct_messages/destroy', $params);
    }

    /**
     * direct_messages/new
     *
     * Sends a new direct message to the specified user from the authenticating user. Requires
     * both the user and text parameters and must be a POST. Returns the sent message in
     * the requested format if successful.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/direct_messages/new
     */
    public function directMessagesNew($params = array())
    {
        return $this->post('direct_messages/new', $params);
    }

    /**
     * friendships/no_retweets/ids
     *
     * Returns a collection of user_ids that the currently authenticated user does not want
     * to receive retweets from. Use POST friendships/update to set the "no retweets" status
     * for a given user account on behalf of the current user.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/no_retweets/ids
     */
    public function friendshipsNoRetweetsIds($params = array())
    {
        return $this->get('friendships/no_retweets/ids', $params);
    }

    /**
     * friends/ids
     *
     * Returns a cursored collection of user IDs for every user the specified user is following
     * (otherwise known as their "friends"). At this time, results are ordered with the
     * most recent following first — however, this ordering is subject to unannounced change
     * and eventual consistency issues....
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friends/ids
     */
    public function friendsIds($params = array())
    {
        return $this->get('friends/ids', $params);
    }

    /**
     * followers/ids
     *
     * Returns a cursored collection of user IDs for every user following the specified
     * user. At this time, results are ordered with the most recent following first — however,
     * this ordering is subject to unannounced change and eventual consistency issues. Results
     * are given in groups of 5,000 user...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/followers/ids
     */
    public function followersIds($params = array())
    {
        return $this->get('followers/ids', $params);
    }

    /**
     * friendships/incoming
     *
     * Returns a collection of numeric IDs for every user who has a pending request to follow
     * the authenticating user.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/incoming
     */
    public function friendshipsIncoming($params = array())
    {
        return $this->get('friendships/incoming', $params);
    }

    /**
     * friendships/outgoing
     *
     * Returns a collection of numeric IDs for every protected user for whom the authenticating
     * user has a pending follow request.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/outgoing
     */
    public function friendshipsOutgoing($params = array())
    {
        return $this->get('friendships/outgoing', $params);
    }

    /**
     * friendships/create
     *
     * Allows the authenticating users to follow the user specified in the ID parameter.
     * Returns the befriended user in the requested format when successful. Returns a string
     * describing the failure condition when unsuccessful. If you are already friends with
     * the user a HTTP 403 may be returned, though for...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/friendships/create
     */
    public function friendshipsCreate($params = array())
    {
        return $this->post('friendships/create', $params);
    }

    /**
     * friendships/destroy
     *
     * Allows the authenticating user to unfollow the user specified in the ID parameter.
     * Returns the unfollowed user in the requested format when successful. Returns a string
     * describing the failure condition when unsuccessful. Actions taken in this method
     * are asynchronous and changes will be eventually...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/friendships/destroy
     */
    public function friendshipsDestroy($params = array())
    {
        return $this->post('friendships/destroy', $params);
    }

    /**
     * friendships/update
     *
     * Allows one to enable or disable retweets and device notifications from the specified
     * user.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/friendships/update
     */
    public function friendshipsUpdate($params = array())
    {
        return $this->post('friendships/update', $params);
    }

    /**
     * friendships/show
     *
     * Returns detailed information about the relationship between two arbitrary users.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/show
     */
    public function friendshipsShow($params = array())
    {
        return $this->get('friendships/show', $params);
    }

    /**
     * friends/list
     *
     * Returns a cursored collection of user objects for every user the specified user is
     * following (otherwise known as their "friends"). At this time, results are ordered
     * with the most recent following first — however, this ordering is subject to unannounced
     * change and eventual consistency issues...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friends/list
     */
    public function friendsList($params = array())
    {
        return $this->get('friends/list', $params);
    }

    /**
     * followers/list
     *
     * Returns a cursored collection of user objects for users following the specified user.
     * At this time, results are ordered with the most recent following first — however,
     * this ordering is subject to unannounced change and eventual consistency issues. Results
     * are given in groups of 20 users and...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/followers/list
     */
    public function followersList($params = array())
    {
        return $this->get('followers/list', $params);
    }

    /**
     * friendships/lookup
     *
     * Returns the relationships of the authenticating user to the comma-separated list
     * of up to 100 screen_names or user_ids provided. Values for connections can be: following,
     * following_requested, followed_by, none, blocking, muting.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/friendships/lookup
     */
    public function friendshipsLookup($params = array())
    {
        return $this->get('friendships/lookup', $params);
    }

    /**
     * account/settings
     *
     * Returns settings (including current trend, geo and sleep time information) for the
     * authenticating user.
     *
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
     * account/verify_credentials
     *
     * Returns an HTTP 200 OK response code and a representation of the requesting user
     * if authentication was successful; returns a 401 status code and an error message
     * if not. Use this method to test if supplied user credentials are valid.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/account/verify_credentials
     */
    public function accountVerifyCredentials($params = array())
    {
        return $this->get('account/verify_credentials', $params);
    }

    /**
     * account/update_delivery_device
     *
     * Sets which device Twitter delivers updates to for the authenticating user. Sending
     * none as the device parameter will disable SMS updates.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_delivery_device
     */
    public function accountUpdateDeliveryDevice($params = array())
    {
        return $this->post('account/update_delivery_device', $params);
    }

    /**
     * account/update_profile
     *
     * Sets values that users are able to set under the "Account" tab of their settings
     * page. Only the parameters specified will be updated.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile
     */
    public function accountUpdateProfile($params = array())
    {
        return $this->post('account/update_profile', $params);
    }

    /**
     * account/update_profile_background_image
     *
     * Updates the authenticating user's profile background image. This method can also
     * be used to enable or disable the profile background image. Although each parameter
     * is marked as optional, at least one of image, tile or use must be provided when making
     * this request.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_background_image
     */
    public function accountUpdateProfileBackgroundImage($params = array())
    {
        return $this->post('account/update_profile_background_image', $params);
    }

    /**
     * account/update_profile_colors
     *
     * Sets one or more hex values that control the color scheme of the authenticating user's
     * profile page on twitter.com. Each parameter's value must be a valid hexidecimal value,
     * and may be either three or six characters (ex: #fff or #ffffff).
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_colors
     */
    public function accountUpdateProfileColors($params = array())
    {
        return $this->post('account/update_profile_colors', $params);
    }

    /**
     * account/update_profile_image
     *
     * Updates the authenticating user's profile image. Note that this method expects raw
     * multipart data, not a URL to an image. This method asynchronously processes the uploaded
     * file before updating the user's profile image URL. You can either update your local
     * cache the next time you request the user's...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_image
     */
    public function accountUpdateProfileImage($params = array())
    {
        return $this->post('account/update_profile_image', $params);
    }

    /**
     * blocks/list
     *
     * Returns a collection of user objects that the authenticating user is blocking. Important
     * On October 15, 2012 this method will become cursored by default, altering the default
     * response format. See Using cursors to navigate collections for more details on how
     * cursoring works.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/blocks/list
     */
    public function blocksList($params = array())
    {
        return $this->get('blocks/list', $params);
    }

    /**
     * blocks/ids
     *
     * Returns an array of numeric user ids the authenticating user is blocking. Important
     * On October 15, 2012 this method will become cursored by default, altering the default
     * response format. See Using cursors to navigate collections for more details on how
     * cursoring works.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/blocks/ids
     */
    public function blocksIds($params = array())
    {
        return $this->get('blocks/ids', $params);
    }

    /**
     * blocks/create
     *
     * Blocks the specified user from following the authenticating user. In addition the
     * blocked user will not show in the authenticating users mentions or timeline (unless
     * retweeted by another user). If a follow or friend relationship exists it is destroyed.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/blocks/create
     */
    public function blocksCreate($params = array())
    {
        return $this->post('blocks/create', $params);
    }

    /**
     * blocks/destroy
     *
     * Un-blocks the user specified in the ID parameter for the authenticating user. Returns
     * the un-blocked user in the requested format when successful.  If relationships existed
     * before the block was instated, they will not be restored.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/blocks/destroy
     */
    public function blocksDestroy($params = array())
    {
        return $this->post('blocks/destroy', $params);
    }

    /**
     * users/lookup
     *
     * Returns fully-hydrated user objects for up to 100 users per request, as specified
     * by comma-separated values passed to the user_id and/or screen_name parameters. This
     * method is especially useful when used in conjunction with collections of user IDs
     * returned from GET friends/ids and GET followers/...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/lookup
     */
    public function usersLookup($params = array())
    {
        return $this->get('users/lookup', $params);
    }

    /**
     * users/show
     *
     * Returns a variety of information about the user specified by the required user_id
     * or screen_name parameter. The author's most recent Tweet will be returned inline
     * when possible. GET users/lookup is used to retrieve a bulk collection of user objects.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/show
     */
    public function usersShow($params = array())
    {
        return $this->get('users/show', $params);
    }

    /**
     * users/search
     *
     * Provides a simple, relevance-based search interface to public user accounts on Twitter.
     * Try querying by topical interest, full name, company name, location, or other criteria.
     * Exact match searches are not supported. Only the first 1,000 matching results are
     * available.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/search
     */
    public function usersSearch($params = array())
    {
        return $this->get('users/search', $params);
    }

    /**
     * users/contributees
     *
     * Returns a collection of users that the specified user can "contribute" to.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/contributees
     */
    public function usersContributees($params = array())
    {
        return $this->get('users/contributees', $params);
    }

    /**
     * users/contributors
     *
     * Returns a collection of users who can contribute to the specified account.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/contributors
     */
    public function usersContributors($params = array())
    {
        return $this->get('users/contributors', $params);
    }

    /**
     * account/remove_profile_banner
     *
     * Removes the uploaded profile banner for the authenticating user. Returns HTTP 200
     * upon success.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/remove_profile_banner
     */
    public function accountRemoveProfileBanner($params = array())
    {
        return $this->post('account/remove_profile_banner', $params);
    }

    /**
     * account/update_profile_banner
     *
     * Uploads a profile banner on behalf of the authenticating user. For best results,
     * upload an
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/account/update_profile_banner
     */
    public function accountUpdateProfileBanner($params = array())
    {
        return $this->post('account/update_profile_banner', $params);
    }

    /**
     * users/profile_banner
     *
     * Returns a map of the available size variations of the specified user's profile banner.
     * If the user has not uploaded a profile banner, a HTTP 404 will be served instead.
     * This method can be used instead of string manipulation on the profile_banner_url
     * returned in user objects as described in User...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/profile_banner
     */
    public function usersProfileBanner($params = array())
    {
        return $this->get('users/profile_banner', $params);
    }

    /**
     * mutes/users/create
     *
     * Mutes the user specified in the ID parameter for the authenticating user. Returns
     * the muted user in the requested format when successful. Returns a string describing
     * the failure condition when unsuccessful. Actions taken in this method are asynchronous
     * and changes will be eventually consistent.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/mutes/users/create
     */
    public function mutesUsersCreate($params = array())
    {
        return $this->post('mutes/users/create', $params);
    }

    /**
     * mutes/users/destroy
     *
     * Un-mutes the user specified in the ID parameter for the authenticating user. Returns
     * the unmuted user in the requested format when successful. Returns a string describing
     * the failure condition when unsuccessful. Actions taken in this method are asynchronous
     * and changes will be eventually...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/mutes/users/destroy
     */
    public function mutesUsersDestroy($params = array())
    {
        return $this->post('mutes/users/destroy', $params);
    }

    /**
     * mutes/users/ids
     *
     * Returns an array of numeric user ids the authenticating user has muted.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/mutes/users/ids
     */
    public function mutesUsersIds($params = array())
    {
        return $this->get('mutes/users/ids', $params);
    }

    /**
     * mutes/users/list
     *
     * Returns an array of user objects the authenticating user has muted.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/mutes/users/list
     */
    public function mutesUsersList($params = array())
    {
        return $this->get('mutes/users/list', $params);
    }

    /**
     * users/suggestions/:slug
     *
     * Access the users in a given category of the Twitter suggested user list. It is recommended
     * that applications cache this data for no more than one hour.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/suggestions/%3Aslug
     */
    public function usersSuggestionsSlug($params = array())
    {
        return $this->get('users/suggestions/:slug', $params);
    }

    /**
     * users/suggestions
     *
     * Access to Twitter's suggested user list. This returns the list of suggested user
     * categories. The category can be used in GET users/suggestions/:slug to get the users
     * in that category.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/suggestions
     */
    public function usersSuggestions($params = array())
    {
        return $this->get('users/suggestions', $params);
    }

    /**
     * users/suggestions/:slug/members
     *
     * Access the users in a given category of the Twitter suggested user list and return
     * their most recent status if they are not a protected user.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/users/suggestions/%3Aslug/members
     */
    public function usersSuggestionsSlugMembers($params = array())
    {
        return $this->get('users/suggestions/:slug/members', $params);
    }

    /**
     * favorites/list
     *
     * Returns the 20 most recent Tweets favorited by the authenticating or specified user.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/favorites/list
     */
    public function favoritesList($params = array())
    {
        return $this->get('favorites/list', $params);
    }

    /**
     * favorites/destroy
     *
     * Un-favorites the status specified in the ID parameter as the authenticating user.
     * Returns the un-favorited status in the requested format when successful. This process
     * invoked by this method is asynchronous. The immediately returned status may not indicate
     * the resultant favorited status of the...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/favorites/destroy
     */
    public function favoritesDestroy($params = array())
    {
        return $this->post('favorites/destroy', $params);
    }

    /**
     * favorites/create
     *
     * Favorites the status specified in the ID parameter as the authenticating user. Returns
     * the favorite status when successful. This process invoked by this method is asynchronous.
     * The immediately returned status may not indicate the resultant favorited status of
     * the tweet. A 200 OK response from this...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/favorites/create
     */
    public function favoritesCreate($params = array())
    {
        return $this->post('favorites/create', $params);
    }

    /**
     * lists/list
     *
     * Returns all lists the authenticating or specified user subscribes to, including their
     * own. The user is specified using the user_id or screen_name parameters. If no user
     * is given, the authenticating user is used. This method used to be GET lists in version
     * 1.0 of the API and has been renamed for...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/list
     */
    public function listsList($params = array())
    {
        return $this->get('lists/list', $params);
    }

    /**
     * lists/statuses
     *
     * Returns a timeline of tweets authored by members of the specified list. Retweets
     * are included by default. Use the include_rts=false parameter to omit retweets. Embedded
     * Timelines is a great way to embed list timelines on your website.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/statuses
     */
    public function listsStatuses($params = array())
    {
        return $this->get('lists/statuses', $params);
    }

    /**
     * lists/members/destroy
     *
     * Removes the specified member from the list. The authenticated user must be the list's
     * owner to remove members from the list.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/destroy
     */
    public function listsMembersDestroy($params = array())
    {
        return $this->post('lists/members/destroy', $params);
    }

    /**
     * lists/memberships
     *
     * Returns the lists the specified user has been added to. If user_id or screen_name
     * are not provided the memberships for the authenticating user are returned.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/memberships
     */
    public function listsMemberships($params = array())
    {
        return $this->get('lists/memberships', $params);
    }

    /**
     * lists/subscribers
     *
     * Returns the subscribers of the specified list. Private list subscribers will only
     * be shown if the authenticated user owns the specified list.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/subscribers
     */
    public function listsSubscribers($params = array())
    {
        return $this->get('lists/subscribers', $params);
    }

    /**
     * lists/subscribers/create
     *
     * Subscribes the authenticated user to the specified list.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/subscribers/create
     */
    public function listsSubscribersCreate($params = array())
    {
        return $this->post('lists/subscribers/create', $params);
    }

    /**
     * lists/subscribers/show
     *
     * Check if the specified user is a subscriber of the specified list. Returns the user
     * if they are subscriber.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/subscribers/show
     */
    public function listsSubscribersShow($params = array())
    {
        return $this->get('lists/subscribers/show', $params);
    }

    /**
     * lists/subscribers/destroy
     *
     * Unsubscribes the authenticated user from the specified list.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/subscribers/destroy
     */
    public function listsSubscribersDestroy($params = array())
    {
        return $this->post('lists/subscribers/destroy', $params);
    }

    /**
     * lists/members/create_all
     *
     * Adds multiple members to a list, by specifying a comma-separated list of member ids
     * or screen names. The authenticated user must own the list to be able to add members
     * to it. Note that lists can't have more than 5,000 members, and you are limited to
     * adding up to 100 members to a list at a time with...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/create_all
     */
    public function listsMembersCreateAll($params = array())
    {
        return $this->post('lists/members/create_all', $params);
    }

    /**
     * lists/members/show
     *
     * Check if the specified user is a member of the specified list.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/members/show
     */
    public function listsMembersShow($params = array())
    {
        return $this->get('lists/members/show', $params);
    }

    /**
     * lists/members
     *
     * Returns the members of the specified list. Private list members will only be shown
     * if the authenticated user owns the specified list.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/members
     */
    public function listsMembers($params = array())
    {
        return $this->get('lists/members', $params);
    }

    /**
     * lists/members/create
     *
     * Add a member to a list. The authenticated user must own the list to be able to add
     * members to it. Note that lists cannot have more than 5,000 members.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/create
     */
    public function listsMembersCreate($params = array())
    {
        return $this->post('lists/members/create', $params);
    }

    /**
     * lists/destroy
     *
     * Deletes the specified list. The authenticated user must own the list to be able to
     * destroy it.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/destroy
     */
    public function listsDestroy($params = array())
    {
        return $this->post('lists/destroy', $params);
    }

    /**
     * lists/update
     *
     * Updates the specified list. The authenticated user must own the list to be able to
     * update it.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/update
     */
    public function listsUpdate($params = array())
    {
        return $this->post('lists/update', $params);
    }

    /**
     * lists/create
     *
     * Creates a new list for the authenticated user. Note that you can't create more than
     * 20 lists per account.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/create
     */
    public function listsCreate($params = array())
    {
        return $this->post('lists/create', $params);
    }

    /**
     * lists/show
     *
     * Returns the specified list. Private lists will only be shown if the authenticated
     * user owns the specified list.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/show
     */
    public function listsShow($params = array())
    {
        return $this->get('lists/show', $params);
    }

    /**
     * lists/subscriptions
     *
     * Obtain a collection of the lists the specified user is subscribed to, 20 lists per
     * page by default.  Does not include the user's own lists.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/subscriptions
     */
    public function listsSubscriptions($params = array())
    {
        return $this->get('lists/subscriptions', $params);
    }

    /**
     * lists/members/destroy_all
     *
     * Removes multiple members from a list, by specifying a comma-separated list of member
     * ids or screen names. The authenticated user must own the list to be able to remove
     * members from it. Note that lists can't have more than 500 members, and you are limited
     * to removing up to 100 members to a list at a...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/lists/members/destroy_all
     */
    public function listsMembersDestroyAll($params = array())
    {
        return $this->post('lists/members/destroy_all', $params);
    }

    /**
     * lists/ownerships
     *
     * Returns the lists owned by the specified Twitter user. Private lists will only be
     * shown if the authenticated user is also the owner of the lists.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/lists/ownerships
     */
    public function listsOwnerships($params = array())
    {
        return $this->get('lists/ownerships', $params);
    }

    /**
     * saved_searches/list
     *
     * Returns the authenticated user's saved search queries.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/saved_searches/list
     */
    public function savedSearchesList($params = array())
    {
        return $this->get('saved_searches/list', $params);
    }

    /**
     * saved_searches/show/:id
     *
     * Retrieve the information for the saved search represented by the given id. The authenticating
     * user must be the owner of saved search ID being requested.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/saved_searches/show/%3Aid
     */
    public function savedSearchesShowId($params = array())
    {
        return $this->get('saved_searches/show/:id', $params);
    }

    /**
     * saved_searches/create
     *
     * Create a new saved search for the authenticated user. A user may only have 25 saved
     * searches.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/saved_searches/create
     */
    public function savedSearchesCreate($params = array())
    {
        return $this->post('saved_searches/create', $params);
    }

    /**
     * saved_searches/destroy/:id
     *
     * Destroys a saved search for the authenticating user. The authenticating user must
     * be the owner of saved search id being destroyed.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/saved_searches/destroy/%3Aid
     */
    public function savedSearchesDestroyId($params = array())
    {
        return $this->post('saved_searches/destroy/:id', $params);
    }

    /**
     * geo/id/:place_id
     *
     * Returns all the information about a known place.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/id/%3Aplace_id
     */
    public function geoIdPlaceId($params = array())
    {
        return $this->get('geo/id/:place_id', $params);
    }

    /**
     * geo/reverse_geocode
     *
     * Given a latitude and a longitude, searches for up to 20 places that can be used as
     * a place_id when updating a status. This request is an informative call and will deliver
     * generalized results about geography.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/reverse_geocode
     */
    public function geoReverseGeocode($params = array())
    {
        return $this->get('geo/reverse_geocode', $params);
    }

    /**
     * geo/search
     *
     * Search for places that can be attached to a statuses/update. Given a latitude and
     * a longitude pair, an IP address, or a name, this request will return a list of all
     * the valid places that can be used as the place_id when updating a status. Conceptually,
     * a query can be made from the user's location...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/search
     */
    public function geoSearch($params = array())
    {
        return $this->get('geo/search', $params);
    }

    /**
     * geo/similar_places
     *
     * Locates places near the given coordinates which are similar in name.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/geo/similar_places
     */
    public function geoSimilarPlaces($params = array())
    {
        return $this->get('geo/similar_places', $params);
    }

    /**
     * geo/place
     *
     * As of December 2nd, 2013, this endpoint is deprecated and retired and no longer functions.
     * Place creation was used infrequently by third party applications and is generally
     * no longer supported on Twitter. Requests will return with status 410 (Gone) with
     * error code 251. Follow the discussion about...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/geo/place
     */
    public function geoPlace($params = array())
    {
        return $this->post('geo/place', $params);
    }

    /**
     * trends/place
     *
     * Returns the top 10 trending topics for a specific WOEID, if trending information
     * is available for it. The response is an array of "trend" objects that encode the
     * name of the trending topic, the query parameter that can be used to search for the
     * topic on Twitter Search, and the Twitter Search URL....
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/trends/place
     */
    public function trendsPlace($params = array())
    {
        return $this->get('trends/place', $params);
    }

    /**
     * trends/available
     *
     * Returns the locations that Twitter has trending topic information for. The response
     * is an array of "locations" that encode the location's WOEID and some other human-readable
     * information such as a canonical name and country the location belongs in. A WOEID
     * is a Yahoo! Where On Earth ID.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/trends/available
     */
    public function trendsAvailable($params = array())
    {
        return $this->get('trends/available', $params);
    }

    /**
     * trends/closest
     *
     * Returns the locations that Twitter has trending topic information for, closest to
     * a specified location. The response is an array of "locations" that encode the location's
     * WOEID and some other human-readable information such as a canonical name and country
     * the location belongs in. A WOEID is a Yahoo...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/trends/closest
     */
    public function trendsClosest($params = array())
    {
        return $this->get('trends/closest', $params);
    }

    /**
     * users/report_spam
     *
     * Report the specified user as a spam account to Twitter. Additionally performs the
     * equivalent of POST blocks/create on behalf of the authenticated user.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/post/users/report_spam
     */
    public function usersReportSpam($params = array())
    {
        return $this->post('users/report_spam', $params);
    }

    /**
     * help/configuration
     *
     * Returns the current configuration used by Twitter including twitter.com slugs which
     * are not usernames, maximum photo resolutions, and t.co URL lengths. It is recommended
     * applications request this endpoint when they are loaded, but no more than once a
     * day.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/configuration
     */
    public function helpConfiguration($params = array())
    {
        return $this->get('help/configuration', $params);
    }

    /**
     * help/languages
     *
     * Returns the list of languages supported by Twitter along with their ISO 639-1 code.
     * The ISO 639-1 code is the two letter value to use if you include lang with any of
     * your requests.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/languages
     */
    public function helpLanguages($params = array())
    {
        return $this->get('help/languages', $params);
    }

    /**
     * help/privacy
     *
     * Returns Twitter's Privacy Policy.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/privacy
     */
    public function helpPrivacy($params = array())
    {
        return $this->get('help/privacy', $params);
    }

    /**
     * help/tos
     *
     * Returns the Twitter Terms of Service in the requested format. These are not the same
     * as the Developer Rules of the Road.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/help/tos
     */
    public function helpTos($params = array())
    {
        return $this->get('help/tos', $params);
    }

    /**
     * application/rate_limit_status
     *
     * Returns the current rate limits for methods belonging to the specified resource families.
     * Each 1.1 API resource belongs to a "resource family" which is indicated in its method
     * documentation. You can typically determine a method's resource family from the first
     * component of the path after the...
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/application/rate_limit_status
     */
    public function applicationRateLimitStatus($params = array())
    {
        return $this->get('application/rate_limit_status', $params);
    }

    /**
     * statuses/lookup
     *
     * Returns fully-hydrated  tweet objects for up to 100 tweets per request, as specified
     * by comma-separated values passed to the id parameter. This method is especially useful
     * to get the details (hydrate) a collection of Tweet IDs. GET statuses/show/:id is
     * used to retrieve a single tweet object.
     *
     * @param array $params
     * @return array
     * @see https://dev.twitter.com/docs/api/1.1/get/statuses/lookup
     */
    public function statusesLookup($params = array())
    {
        return $this->get('statuses/lookup', $params);
    }
}
