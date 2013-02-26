<?php

require_once __DIR__ . '/bootstrap.php';

use Twient\Twitter;

try {
    $twitter = new Twitter();
    echo sprintf("Use %s class\r\n", $twitter->getRequestClass());
    $oauth = $twitter->oAuth(APP_CONSUMER_KEY, APP_CONSUMER_SECRET);
    $requestToken = $oauth->getRequestToken();
    $url = $oauth->getAuthorizeUrl($requestToken);
    echo 'Go to ' . $url . PHP_EOL;
    echo 'Input PIN: ';
    $pin = trim(fgets(STDIN, 4096));
    if (empty($pin)) {
        exit;
    }
    $token = $oauth->getAccessToken($pin);
    echo 'Your token is "' . $token['oauth_token'] . '"' . PHP_EOL;
    echo 'Your secret token is "' . $token['oauth_token_secret'] . '"' . PHP_EOL;
    echo 'Input Status: ';
    $status = trim(fgets(STDIN, 4096));
    if (empty($status)) {
        exit;
    }
    $twitter->postStatusesUpdate(array('status' => $status));
} catch (Exception $e) {
    echo $e . PHP_EOL;
}
