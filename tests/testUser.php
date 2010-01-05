<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../src/Twitter.php';
$t = new lime_test(10, new lime_output_color());

try {
	$consumer_key = 'Y8rq4tcFLhqVEUKV4FvZA';
	$consumer_secret = 'l2PLP0BCr2pJ5amdSDIRDbtaBVku6QEf3iCNvo8CzeE';
	$oauth_token = "101258778-MRXwbVyJoqARe7TC9x2VT3i9NvWMZo0lLdds21U6";
	$oauth_token_secret = "fafYoa7AKVrSMZ09YXPjzgOw7Q4gIo9A6QN08rll8uI";
	
	$twitter = new Twitter();
	$twitter->oAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	
	// User Methods
	$u = $twitter->call('users/show',array('id'=>'makotokw'));
	$t->is($u->name, 'Makoto Kawasaki', 'users/show');
	$u = $twitter->call('users/search',array('q'=>'makotokw'));
	$t->ok(count($u)>0, 'users/search');
	$f = $twitter->call('statuses/friends',array('id'=>'makotokw'));
	$t->ok(count($f)>0, 'statuses/friends');
	$f = $twitter->call('statuses/followers',array('id'=>'makotokw'));
	$t->ok(count($f)>0, 'statuses/followers');
	
	// Friendship Methods
	$u = $twitter->call('friendships/destroy',array('id'=>'makotokw'));
	$t->is($u->name, 'Makoto Kawasaki', 'friendships/destroy');
	$u = $twitter->call('friendships/create',array('id'=>'makotokw'));
	$t->is($u->name, 'Makoto Kawasaki', 'friendships/create');
	$f = $twitter->call('friendships/exists',array('user_a'=>'php_twient','user_b'=>'makotokw'));
	$t->is($f, true, 'friendships/exists');
	$f = $twitter->call('friendships/show',array('source_screen_name'=>'php_twient','target_screen_name'=>'makotokw'));
	$t->isa_ok($f->relationship,'stdClass','friendships/show');
	
	// Social Graph Method
	$g = $twitter->call('friends/ids',array('id'=>'makotokw'));
	$t->ok(count($g)>0,'friends/ids');
	$g = $twitter->call('followers/ids',array('id'=>'makotokw'));
	$t->ok(count($g)>0,'followers/ids');
	
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
