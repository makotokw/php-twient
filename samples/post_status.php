<?php

require_once __DIR__ .'/bootstrap.php';

use Twient\Twitter;

try {
	// @php-twilent
	$consumer_key = 'Y8rq4tcFLhqVEUKV4FvZA';
	$consumer_secret = 'l2PLP0BCr2pJ5amdSDIRDbtaBVku6QEf3iCNvo8CzeE';

	$twitter = new Twitter();
	echo sprintf("Use %s class\r\n", $twitter->getRequestClass());
	$oauth = $twitter->oAuth($consumer_key, $consumer_secret);
	$requestToken = $oauth->getRequestToken();
	$url = $oauth->getAuthorizeUrl($requestToken);
	echo 'Go to '.$url."\r\n";
	echo 'Input PIN: ';
	$pin = trim(fgets(STDIN,4096));
	if (empty($pin)) exit;
	$token = $oauth->getAccessToken($pin);
	echo 'Your token is "'.$token['oauth_token']."\"\r\n";
	echo 'Your secret token is "'.$token['oauth_token_secret']."\"\r\n";
	echo 'Input Status: ';
	$status = trim(fgets(STDIN,4096));
	if (empty($status)) exit;
	$twitter->call('statuses/update',array('status'=>$status));

} catch (Exception $e) {
	echo $e.PHP_EOL;
}
