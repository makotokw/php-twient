php-twient
==============

php-twient is a php twitter client library.

FEATURES
==========

 * Composer package
 * Supports namespace for PHP 5.3+
 * Supports basicAuth and OAuth both
 * Should work with/without php_curl (detects php_curl automatically)
 * Supported Search API and REST API by Twitter::call method
 * Supported Streaming API by Twitter::streaming method

SAMPLE CODE
============

Using without authentication for REST/Search API
----------------------------------------------------

	<?php
	use Twient\Twitter;

	$twitter = new Twitter();
	$r = $twitter->call('search',array('q'=>'Sushi'));
	foreach ($r['results'] as $status) {
		echo $status['from_user'].': ';
		echo $status['text'].PHP_EOL;
	}
	?>

Gets your timeline with OAuth
----------------------------------------------------

	<?php
	use Twient\Twitter;
	
	$consumer_key = 'consumer key for your application';
	$consumer_secret = 'consumer secret for your application';
	$oauth_token = 'oauth token for your account';
	$oauth_token_secret = 'oauth token secret for your account';
	
	$twitter = new Twitter();
	$twitter->oAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	$statuses = $twitter->call('statuses/home_timeline');
	foreach ($statuses as $status) {
		echo $status['user']['name'].': ';
		echo $status['text'].PHP_EOL;
	}
	?>

Using Streaming API with BasicAuth
----------------------------------------------------

	<?php
	use Twient\Twitter;
	
	$user = 'your account';
	$pass = 'your password';
	
	$twitter = new Twitter();
	$twitter->basicAuth($user, $pass);
	$twitter->streaming('statuses/filter',array('track'=>'Sushi,Japan'),'_callback');
	function _callback($status) {
		static $count = 0;
		echo $status['user']['name'].':'.$status['text'] . PHP_EOL;
		return ($count++<5);
	}
	?>
	
Extends to use Twitter API that is not defined on twient package
-----------------------------------------------------------

	<?php
	use Twient\Twitter;

	$twitter = new Twitter();
	$twitter->extend(array(
		'statuses/:id/retweeted_by'=>array('url'=>Twitter::API_URL,'required'=>array('id'),'$1'=>'id'),
		'statuses/:id/retweeted_by/ids'=>array('url'=>Twitter::API_URL, required'=>array('id'),'$1'=>'id'),
	));
	?>

LIMITATIONS
===========

 * Not supported on PHP5.2 or older
 * No test for statuses/firehose and statuses/retweet due to not have access level

TODO
===========

 * Support List Methods
 * Support List Members Methods
 * Support List Subscribers Methods
 * Support Direct Message Methods
 * Support new Friendship Methods
 * Support Friendship Methods
 * Support Account Methods
 * Support Favorite Methods
 * Support Notification Methods
 * Support Block Methods
 * Support Spam Reporting Methods
 * Support Saved Searches Methods
 * Support OAuth Methods
 * Support Geo methods
 * Support Help Methods

HISTORY
============

v0.4
----------------

 * Updated to Composer package
 * Required PHP5.3+


v0.3.1
----------------

 * Uses the Services_JSON instead of json_decode when PHP < 5.2.0

v0.3
----------------

 * Suppoted to override API settings: Twitter->apis and Twitter->streamingApis are public attributes
 * Fixed that OAuth part have deprecated function on PHP 5.3 or later
 * Supported Trends Methods
 * Updated Status Methods and User Methods

v0.2
----------------

 * Supported Search API.
 * Supported User, Friendshop and Social Graph Methods of REST API.
 * Supported Streaming API.
 * Changed call method returns associative arrays instead of objects.
 * Added assoc flag to return associative arrays or objects.

v0.1
----------------

 * Initial Release

LICENSE
=========

MIT License
See also LICENSE file