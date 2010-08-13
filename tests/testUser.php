<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../lib/Twitter.php';
$t = new lime_test(10, new lime_output_color());

try {
	// consumer: php-twient
	$consumer_key = 'Y8rq4tcFLhqVEUKV4FvZA';
	$consumer_secret = 'l2PLP0BCr2pJ5amdSDIRDbtaBVku6QEf3iCNvo8CzeE';
	// user: http://twitter.com/php_twient
	$oauth_token = "101258778-MRXwbVyJoqARe7TC9x2VT3i9NvWMZo0lLdds21U6";
	$oauth_token_secret = "fafYoa7AKVrSMZ09YXPjzgOw7Q4gIo9A6QN08rll8uI";
	
	$twitter = new Twitter();
	$twitter->useAssociativeArray();
	$twitter->oAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	
	// User Methods
	$u = $twitter->call('users/show',array('id'=>'makotokw'));
	$t->is($u['name'], 'Makoto Kawasaki', 'users/show');
	$u = $twitter->call('users/search',array('q'=>'makotokw'));
	$t->ok(count($u)>0, 'users/search');
	$u = $twitter->call('users/lookup',array('screen_name'=>'makotokw,makoto_kw'));
	$t->ok(count($u)==2, 'users/lookup');
	$u = $twitter->call('users/suggestions');
	$t->ok(isset($u[0]['slug']), 'users/suggestions');
	$u = $twitter->call('users/suggestions/category',array('slug'=>'twitter'));
	$t->ok(isset($u['users'][0]['id']), 'users/suggestions/category');	
	$f = $twitter->call('statuses/friends',array('id'=>'makotokw'));
	$t->ok(count($f)>0, 'statuses/friends');
	$f = $twitter->call('statuses/followers',array('id'=>'makotokw'));
	$t->ok(count($f)>0, 'statuses/followers');
	
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
