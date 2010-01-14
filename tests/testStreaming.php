<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../src/Twitter.php';
$t = new lime_test(3, new lime_output_color());
try {
	if (!$t->is(count($argv),3,'Usage: script.php acount password')) {
		exit;
	}
	$twitter = new Twitter();
	$twitter->basicAuth($argv[1], $argv[2]);
	$count = 0;
	$t->ok($twitter->call('statuses/filter',array('track'=>'Sushi,Japan'),'_callback'),'statuses/filter');
	$count = 0;
	$t->ok($twitter->call('statuses/sample',array(),'_callback_ja'),'statuses/sample');
} catch (Twitter_Exception $e) {
	$t->fail($e);
} catch (Exception $e) {
	$t->fail($e);
}

function _callback($twitter) {
	global $count;
	echo $twitter['user']['name'].':'.$twitter['text'] . PHP_EOL;
	return ($count++<5);
}

function _callback_ja($twitter) {
	global $count;
	if(preg_match('/[ァ-ヶーぁ-ん]/u',$twitter['text'])) {
		echo $twitter['user']['name'].':'.$twitter['text'] . PHP_EOL;
		$count++;
	}
	return ($count<5);
}
