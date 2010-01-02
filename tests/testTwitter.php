<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../src/Twitter.php';
$t = new lime_test(13, new lime_output_color());

try {
	$consumer_key = 'Y8rq4tcFLhqVEUKV4FvZA';
	$consumer_secret = 'l2PLP0BCr2pJ5amdSDIRDbtaBVku6QEf3iCNvo8CzeE';
	$oauth_token = "101258778-MRXwbVyJoqARe7TC9x2VT3i9NvWMZo0lLdds21U6";
	$oauth_token_secret = "fafYoa7AKVrSMZ09YXPjzgOw7Q4gIo9A6QN08rll8uI";
	
	$twitter = new Twitter();
	$twitter->oAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	$t->is(count($twitter->call('statuses/public_timeline')),20,'statuses/public_timeline');
	$t->is(count($twitter->call('statuses/home_timeline')),20,'statuses/home_timeline');
	$t->is(count($twitter->call('statuses/user_timeline')),20,'statuses/user_timeline');
	$t->is(count($twitter->call('statuses/user_timeline',array('id'=>'makoto_kw'))),20,'statuses/user_timeline by id');
	$t->is(count($twitter->call('statuses/mentions')),20,'statuses/mentions');
	$t->is(count($twitter->call('statuses/retweeted_by_me')),20,'statuses/retweeted_by_me');
	$t->is(count($twitter->call('statuses/retweeted_to_me')),20,'statuses/retweeted_to_me');
	$t->is(count($twitter->call('statuses/retweets_of_me')),20,'statuses/retweets_of_me');
	$t->is(count($twitter->call('statuses/update',array('status'=>'posted at '.time()))),1,'statuses/update');
	
	$twitter->setRequestClass('Twitter_Request');
	$t->is(count($twitter->call('statuses/public_timeline')),20,'Twitter_Reqest get');
	$t->is(count($twitter->call('statuses/update',array('status'=>'Twitter_Reqest posted at '.time()))),1,'statuses/update');
	$twitter->setRequestClass('Twitter_Request_Curl');
	$t->is(count($twitter->call('statuses/public_timeline')),20,'Twitter_Request_Curl get');
	$t->is(count($twitter->call('statuses/update',array('status'=>'Twitter_Request_Curl posted at '.time()))),1,'statuses/update');
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
