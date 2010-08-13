<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../lib/Twitter.php';
$t = new lime_test(2, new lime_output_color());

try {
	$twitter = new Twitter();
	foreach (array('Twitter_Request','Twitter_Request_Curl') as $class) {
		$twitter->setRequestClass($class);
		$r = $twitter->call('search',array('q'=>'Sushi'));
		$t->ok(count($r['results']),'search');
	}
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}
