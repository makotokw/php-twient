<?php
require_once dirname(__FILE__).'/../lib/Twitter.php';
try {
	$twitter = new Twitter();
	echo sprintf("Use %s class\r\n", $twitter->getRequestClass());
	
	echo 'Account: '; $id = rtrim(fgets(STDIN,4096));
	echo 'Password: '; $pw = rtrim(fgets(STDIN,4096));
	$twitter->basicAuth($id, $pw);
	echo 'Input Status: '; $status = rtrim(fgets(STDIN,4096));
	if (empty($status)) {
		exit;
	}
	$twitter->call('statuses/update',array('status'=>$status));
} catch (Exception $e) {
	echo $e;
}
