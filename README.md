php-twient
==============

php-twient is a php5.3+ twitter client library and Composer package.

FEATURES
==========

 * Composer package
 * Uses namespace for PHP 5.3+
 * PSR-2 Coding Standard
 * Supports OAuth
 * Supports Streaming APIs
 * Should work with/without php_curl (detects php_curl automatically)

Usage
============

Install by using Composer
----------------------------------------------------

Composer: http://getcomposer.org/

create blew composer.json on your project directory and execute ``composer install``

    /path/to/project/composer.json
    {
        "require": {
            "makotokw/twient": "dev-master"
        }
    }


Get timeline
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
        echo $status['user']['name'].': '.$status['text'].PHP_EOL;
    }
    ?>


Post status (Tweet)
----------------------------------------------------

    $twitter->call('statuses/update', array('status' => 'tweet by php-twient'));


Using Streaming API with closure
----------------------------------------------------

The streaming method is terminated when callback returns false.

    $twitter->streaming(
        'statuses/filter',
        array('track' => 'Sushi,Japan'),
        function ($twitter, $status) {
            static $count = 0;
            echo $status['user']['name'] . ':' . $status['text'] . PHP_EOL;
            return ($count++ < 5);
        }
    );


Alternative API
----------------------------------------------------

    use Twient\Twitter\V1dot1 as Twitter;

    $twitter = new Twitter();
    ...
    $twitter->statusesHomeTimeline();       // instead of "call('statuses/home_timeline')"
    $twitter->statusesUpdate(...);          // instead of "call('statuses/update', ...)"
    $twitter->streamingStatusesFilter(...);    // instead of "streaming('statuses/filter', ...)"


HISTORY
============

v0.5
----------------

 * Supported Twitter API v1.1

v0.4
----------------

 * Updated to Composer package
 * Required PHP5.3+
 * PSR-2 Coding Standard


v0.3.1
----------------

 * Uses the Services_JSON instead of json_decode when PHP < 5.2.0

v0.3
----------------

 * Fixed that OAuth part have deprecated function on PHP 5.3 or later
 * Supported Trends Methods
 * Updated Status Methods and User Methods

v0.2
----------------

 * Supported Search API.
 * Supported User, Friendship and Social Graph Methods of REST API.
 * Supported Streaming API.
 * Changed call method returns associative arrays instead of objects.
 * Added assoc flag to return associative arrays or objects.

v0.1
----------------

 * Initial Release

LICENSE
=========

The MIT License (MIT)  
See also LICENSE file