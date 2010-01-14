<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../src/Twitter.php';
$t = new lime_test(10, new lime_output_color());

try {
	$twitter = new Twitter();
	foreach (array('Twitter_Request','Twitter_Request_Curl') as $class) {
		$twitter->setRequestClass($class);
		$r = $twitter->call('search',array('q'=>'Sushi'));
		$t->ok(count($r->results),'search');
		$r = $twitter->call('trends');
		$t->ok(count($r->trends),'trends');
		$r = $twitter->call('trends/current');
		$t->ok(count($r->trends),'trends/current');
		$r = $twitter->call('trends/daily');
		$t->ok(count($r->trends),'trends/daily');
		$r = $twitter->call('trends/weekly');
		$t->ok(count($r->trends),'trends/weekly');
	}
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
