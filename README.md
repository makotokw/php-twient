php-twient
==============

php-twient is a php twitter client library.

FEATURES
==========

 * PHP5 Object Oriented
 * Supports basicAuth and OAuth both
 * Supports autoload
 * Should work with/without php_curl (detects php_curl automatically)
 * Supported Search API and REST API by Twitter::call method
 * Supported Streaming API by Twitter::streaming method

SAMPLE CODE
============

Using without authentication for REST/Search API
----------------------------------------------------

	<?php
	require_once 'Twitter.php';
	$twitter = new Twitter();
	$statuses = $twitter->call('statuses/public_timeline');
	foreach ($statuses as $status) {
		echo $status['user']['name'].': ';
		echo $status['text'].PHP_EOL;
	}
	$r = $twitter->call('search',array('q'=>'Sushi'));
	foreach ($r['results'] as $status) {
		echo $status['from_user'].': ';
		echo $status['text'].PHP_EOL;
	}
	?>

Gets your timeline with OAuth
----------------------------------------------------

	<?php
	require_once 'Twitter.php';
	
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
	require_once 'Twitter.php';
	
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

LIMITATIONS
===========

 * Not supported on PHP4
 * No test for statuses/firehose and statuses/retweet due to not have access level

TODO
===========

 * Fix that OAuth part have deprecated function on PHP 5.3 or later
 * Surpport List Methods
 * Surpport List Members Methods
 * Surpport List Subscribers Methods
 * Surpport Direct Message Methods
 * Surpport Friendship Methods
 * Surpport Account Methods
 * Surpport Favorite Methods
 * Surpport Notification Methods
 * Surpport Block Methods
 * Surpport Spam Reporting Methods
 * Surpport Saved Searches Methods
 * Surpport Help Methods

HISTORY
============

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

New BSD License <http://www.opensource.org/licenses/bsd-license.php>  
See also LICENSE file