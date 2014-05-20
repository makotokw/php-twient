<?php

require_once __DIR__ . '/bootstrap.php';

use Makotokw\Twient\Twitter\V1dot1 as Twitter;

try {
    $twitter = new Twitter();
    $twitter->oAuth(
        APP_CONSUMER_KEY,
        APP_CONSUMER_SECRET,
        USER_TOKEN,
        USER_SECRET
    );
    $results = $twitter->searchTweets(array('q'=>'Sushi'));
    foreach ($results['statuses'] as $status) {
        echo $status['user']['name'].': ';
        echo $status['text'].PHP_EOL;
    }
} catch (Exception $e) {
    echo $e . PHP_EOL;
}

