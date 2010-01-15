<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../src/Twitter.php';
$t = new lime_test(3, new lime_output_color());

try {
	$twitter = new Twitter();
	$statuses = $twitter->call('statuses/public_timeline');
	$t->isa_ok($statuses[0],'array','default');
	$twitter->useAssociativeArray();
	$statuses = $twitter->call('statuses/public_timeline');
	$t->isa_ok($statuses[0],'array','useAssociativeArray(true)');
	$twitter->useAssociativeArray(false);
	$statuses = $twitter->call('statuses/public_timeline');
	$t->isa_ok($statuses[0],'stdClass','useAssociativeArray(false)');
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
