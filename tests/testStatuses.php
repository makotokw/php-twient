<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../lib/Twitter.php';
$t = new lime_test(12, new lime_output_color());

try {
	// php-twient
	$consumer_key = 'Y8rq4tcFLhqVEUKV4FvZA';
	$consumer_secret = 'l2PLP0BCr2pJ5amdSDIRDbtaBVku6QEf3iCNvo8CzeE';
	// user: http://twitter.com/php_twient
	$oauth_token = "101258778-MRXwbVyJoqARe7TC9x2VT3i9NvWMZo0lLdds21U6";
	$oauth_token_secret = "fafYoa7AKVrSMZ09YXPjzgOw7Q4gIo9A6QN08rll8uI";
	
	$twitter = new Twitter();
	$twitter->useAssociativeArray();
	$twitter->oAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	
	$twitter->extend(array(
		'statuses/id/retweeted_by'=>array('url'=>Twitter::API_URL,'method'=>'statuses/#id/retweeted_by','required'=>array('id'),'#id'=>'id'),
		'statuses/id/retweeted_by/ids'=>array('url'=>Twitter::API_URL,'method'=>'statuses/#id/retweeted_by/ids','required'=>array('id'),'#id'=>'id'),
	));
	foreach (array('Twitter_Request','Twitter_Request_Curl') as $class) {
		$twitter->setRequestClass($class);
		$s = $twitter->call('statuses/show',array('id'=>'21036914236'));
		$t->ok(isset($s['id']),$class.' statuses/show');
		$s = $twitter->call('statuses/update',array('status'=>$class.' posted at '.time()));
		$t->ok(isset($s['id']),$class.' statuses/update');
		sleep(1);
		if (isset($s['id'])) {
			$s = $twitter->call('statuses/destroy',array('id'=>$s['id']));
			$t->ok(isset($s['id']),$class.' statuses/destroy');
		}
		$users = $twitter->call('statuses/id/retweeted_by',array('id'=>'21036914236'));
		$t->ok(isset($users[0]['id']),$class.' statuses/id/retweeted_by');
		$users = $twitter->call('statuses/id/retweeted_by/ids',array('id'=>'21036914236'));
		$t->ok(count($users),$class.' statuses/id/retweeted_by');
		
		exit;
	}
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
