<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../lib/Twitter.php';
$t = new lime_test(3, new lime_output_color());
try {
	// if (!$t->is(count($argv),3,'Usage: php testStreaming.php [acount] [password]')) {
	// 	exit;
	// }
	// php-twient
	$consumer_key = 'Y8rq4tcFLhqVEUKV4FvZA';
	$consumer_secret = 'l2PLP0BCr2pJ5amdSDIRDbtaBVku6QEf3iCNvo8CzeE';
	// user: http://twitter.com/php_twient
	$oauth_token = "101258778-MRXwbVyJoqARe7TC9x2VT3i9NvWMZo0lLdds21U6";
	$oauth_token_secret = "fafYoa7AKVrSMZ09YXPjzgOw7Q4gIo9A6QN08rll8uI";
	
	$twitter = new Twitter();
	$twitter->useAssociativeArray();
	$twitter->oAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	// $twitter->basicAuth($argv[1], $argv[2]);
	$count = 0;
	$t->ok($twitter->streaming('statuses/filter',array('track'=>'Sushi,Japan'),'_callback'),'statuses/filter');
	$count = 0;
	$t->ok($twitter->streaming('statuses/sample',array(),'_callback_ja'),'statuses/sample');
	// $count = 0;
	// $t->ok($twitter->streaming('user',array(),'_callback'),'user');
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}

function _callback($twitter, $status) {
	global $count;
	if (isset($status['text'])) {
		echo $status['user']['name'].':'.$status['text'] . PHP_EOL;
	}
	return ($count++<5);
}

function _callback_ja($twitter, $status) {
	global $count;
	if (isset($status['text'])) {
		if(preg_match('/[ァ-ヶーぁ-ん]/u',$status['text'])) {
			echo $status['user']['name'].':'.$status['text'] . PHP_EOL;
			$count++;
		}
	}
	return ($count<5);
}
