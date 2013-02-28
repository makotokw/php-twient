<?php

namespace Twient;

// php-twient Application
define('APP_CONSUMER_KEY', 'Y8rq4tcFLhqVEUKV4FvZA');
define('APP_CONSUMER_SECRET', 'l2PLP0BCr2pJ5amdSDIRDbtaBVku6QEf3iCNvo8CzeE');
// @php-twient: http://twitter.com/php_twient
define('USER_TOKEN', '101258778-MRXwbVyJoqARe7TC9x2VT3i9NvWMZo0lLdds21U6');
define('USER_SECRET', 'fafYoa7AKVrSMZ09YXPjzgOw7Q4gIo9A6QN08rll8uI');

class TwitterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Twient\Twitter
     */
    public $twitter;
    public $requestClasses;

    public function setUp()
    {
        error_reporting(E_ALL);
        $twitter = new Twitter();
        $twitter->oAuth(
            APP_CONSUMER_KEY,
            APP_CONSUMER_SECRET,
            USER_TOKEN,
            USER_SECRET
        );
        $this->twitter = $twitter;
        $this->requestClasses = array('\Twient\Request\BaseRequest');
        if (function_exists('curl_init')) {
            $this->requestClasses[] = '\Twient\Request\CurlRequest';
        }
    }

    /**
     * @group timelines
     */
    public function testTimelines()
    {
        $twitter = $this->twitter;
        $statuses = $twitter->call('statuses/home_timeline', array('count' => 1));
        $this->assertTrue(count($statuses) > 0, 'statuses/home_timeline');
        $statuses = $twitter->call('statuses/user_timeline', array('screen_name' => 'makoto_kw', 'count' => 1));
        $this->assertTrue(count($statuses) > 0, 'statuses/user_timeline');
    }

    /**
     * @group tweets
     */
    public function testTweets()
    {
        try {
            $twitter = $this->twitter;
            foreach ($this->requestClasses as $class) {
                $twitter->setRequestClass($class);

                $s = $twitter->call('statuses/show/:id', array('id' => '21036914236'));
                $this->assertTrue(isset($s['id_str']), $class . ' statuses/show/:id');

                $s = $twitter->call('statuses/update', array('status' => $class . ' posted at ' . time()));
                $this->assertTrue(isset($s['id_str']), $class . ' statuses/update');
                sleep(1);

                if (isset($s['id_str'])) {
                    $s = $twitter->call('statuses/destroy/:id', array('id' => $s['id_str']));
                    $this->assertTrue(isset($s['id_str']), $class . ' statuses/destroy/:id');
                }
                $s = $twitter->call('statuses/retweets/:id', array('id' => '21036914236'));
                $this->assertTrue(isset($s[0]['id_str']), $class . ' statuses/retweets/:id');
                sleep(1);
            }
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @group friends
     */
    public function testFriends()
    {
        try {
            $twitter = $this->twitter;

            $g = $twitter->call('followers/ids', array('screen_name' => 'makotokw'));
            $this->assertTrue(count($g) > 0, 'followers/ids');

            $g = $twitter->call('friends/ids', array('screen_name' => 'makotokw'));
            $this->assertTrue(count($g) > 0, 'friends/ids');

            // Friendship Methods
            $u = $twitter->call('friendships/destroy', array('screen_name' => 'makotokw'));
            $this->assertEquals($u['name'], 'Makoto Kawasaki', 'friendships/destroy');
            sleep(1);

            $u = $twitter->call('friendships/create', array('screen_name' => 'makotokw'));
            $this->assertEquals($u['name'], 'Makoto Kawasaki', 'friendships/create');

            $f = $twitter->call(
                'friendships/show',
                array('source_screen_name' => 'php_twient', 'target_screen_name' => 'makotokw')
            );
            $this->assertEquals(@$f['relationship']['target']['screen_name'], 'makotokw', 'friendships/show');

        } catch (Exception $e) {
            $this->fail($e->getMessage());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    /**
     * @group suggestions
     */
    public function testSuggestions()
    {
        try {
            $twitter = $this->twitter;

            $categories = $twitter->call('users/suggestions');
            $this->assertTrue(count($categories) > 5, 'users/suggestions');

            $s = $twitter->call('users/suggestions/:slug', array('slug' => 'twitter'));
            $this->assertTrue(count($s['users']) > 1, 'users/suggestions/:slug');

            $users = $twitter->call('users/suggestions/:slug/members', array('slug' => 'twitter'));
            $this->assertTrue(count($users) > 1, 'users/suggestions/:slug/members');
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @group trends
     */
    public function testTrends()
    {
        try {
            $twitter = $this->twitter;
            $r = $twitter->call('trends/available');
            $this->assertTrue(isset($r[0]['woeid']), 'trends/available');
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    /**
     * @group search
     */
    public function testSearch()
    {
        try {
            $twitter = $this->twitter;
            $r = $twitter->call('search/tweets', array('q' => 'Sushi'));
            $this->assertTrue(count($r['statuses']) > 0, 'search/tweets');
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @group streaming
     */
    public function testStreaming()
    {
        $twitter = $this->twitter;
        $this->assertTrue(
            $twitter->streaming(
                'statuses/filter',
                array('track' => 'Sushi,Japan'),
                function ($twitter, $status) {
                    static $count = 0;
                    if (isset($status['text'])) {
                        echo $status['user']['name'] . ':' . $status['text'] . PHP_EOL;
                    }
                    return ($count++ < 5);
                }
            ) > 0,
            'statuses/filter'
        );
        $this->assertTrue(
            $twitter->streaming(
                'statuses/sample',
                array(),
                function ($twitter, $status) {
                    static $count = 0;
                    if (isset($status['text'])) {
                        if (preg_match('/[ァ-ヶーぁ-ん]/u', $status['text'])) {
                            echo $status['user']['name'] . ':' . $status['text'] . PHP_EOL;
                            $count++;
                        }
                    }
                    return ($count < 5);
                }
            ) > 0,
            'statuses/sample'
        );
    }
}
