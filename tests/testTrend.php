<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../lib/Twitter.php';
$t = new lime_test(12, new lime_output_color());

try {
	$twitter = new Twitter();
	foreach (array('Twitter_Request','Twitter_Request_Curl') as $class) {
		$twitter->setRequestClass($class);
		$r = $twitter->call('trends/available');
		$t->ok(isset($r[0]['woeid']),'trends/available');
		$r = $twitter->call('trends/locations',array('woeid'=>2487956));
		$t->ok(count($r[0]['trends']),'trends/locations');
		$r = $twitter->call('trends');
		$t->ok(count($r['trends']),'trends');
		$r = $twitter->call('trends/current');
		$t->ok(count($r['trends']),'trends/current');
		$r = $twitter->call('trends/daily');
		$t->ok(count($r['trends']),'trends/daily');
		$r = $twitter->call('trends/weekly');
		$t->ok(count($r['trends']),'trends/weekly');
	}
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
