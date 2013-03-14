<?php

require_once __DIR__ . '/bootstrap.php';

use Twient\Twitter\V1dot1 as Twitter;

try {
    $twitter = new Twitter();
    $twitter->oAuth(
        APP_CONSUMER_KEY,
        APP_CONSUMER_SECRET,
        USER_TOKEN,
        USER_SECRET
    );
    $twitter->streamingStatusesFilter(
        array('track' => 'Sushi,Japan'),
        function ($twitter, $status) {
            static $count = 0;
            echo $status['user']['name'] . ':' . $status['text'] . PHP_EOL;
            return ($count++ < 5);
        }
    );
} catch (Exception $e) {
    echo $e . PHP_EOL;
}
