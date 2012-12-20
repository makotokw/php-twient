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
	var $twitter;
	var $requestClasses;

	public function setUp()
	{
		error_reporting(E_ALL);
		$twitter = new Twitter();
		$twitter->oAuth(
			APP_CONSUMER_KEY,
			APP_CONSUMER_SECRET,
			USER_TOKEN,
			USER_SECRET);
		$twitter->useAssociativeArray();
		$this->twitter = $twitter;
		$this->requestClasses = array('\Twient\Request\BaseRequest');
		$this->requestClasses = array();
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

		$statuses = $twitter->call('statuses/home_timeline');
		$this->assertTrue(count($statuses) > 5,'statuses/home_timeline');

		// test account has no reteets...

//		$statuses = $twitter->call('statuses/retweeted_by_me');
//		$this->assertTrue(count($statuses) > 5,'statuses/retweeted_by_me');

//		$statuses = $twitter->call('statuses/retweeted_to_me');
//		$this->assertTrue(count($statuses) > 5,'statuses/retweeted_to_me');

//		$statuses = $twitter->call('statuses/retweets_of_me');
//		$this->assertTrue(count($statuses) > 5,'statuses/retweets_of_me');

		$statuses = $twitter->call('statuses/user_timeline');
		$this->assertTrue(count($statuses) > 5,'statuses/user_timeline',array('id'=>'makoto_kw'));

		$statuses = $twitter->call('statuses/retweeted_to_user',array('id'=>'makoto_kw'));
		$this->assertTrue(count($statuses) > 5,'statuses/retweeted_to_user');

		$statuses = $twitter->call('statuses/retweeted_by_user',array('id'=>'makoto_kw'));
		$this->assertTrue(count($statuses) > 5,'statuses/retweeted_by_user');
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

				$s = $twitter->call('statuses/show/:id',array('id'=>'21036914236'));
				$this->assertTrue(isset($s['id_str']),$class.' statuses/show');

				$s = $twitter->call('statuses/update',array('status'=>$class.' posted at '.time()));
				$this->assertTrue(isset($s['id_str']),$class.' statuses/update');
				sleep(1);

				if (isset($s['id_str'])) {
					$s = $twitter->call('statuses/destroy/:id',array('id'=>$s['id_str']));
					$this->assertTrue(isset($s['id_str']),$class.' statuses/destroy');
				}

				$users = $twitter->call('statuses/:id/retweeted_by',array('id'=>'21036914236'));
				$this->assertTrue(isset($users[0]['id']), $class.' statuses/id/retweeted_by');

				$users = $twitter->call('statuses/:id/retweeted_by/ids',array('id'=>'21036914236'));
				$this->assertTrue(count($users)>0,$class.' statuses/id/retweeted_by/ids');
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

			$g = $twitter->call('followers/ids',array('id'=>'makotokw'));
			$this->assertTrue(count($g)>0,'followers/ids');

			$g = $twitter->call('friends/ids',array('id'=>'makotokw'));
			$this->assertTrue(count($g)>0,'friends/ids');

			// Friendship Methods
			$u = $twitter->call('friendships/destroy',array('id'=>'makotokw'));
			$this->assertEquals($u['name'], 'Makoto Kawasaki', 'friendships/destroy');
			sleep(1);

			$u = $twitter->call('friendships/create',array('id'=>'makotokw'));
			$this->assertEquals($u['name'], 'Makoto Kawasaki', 'friendships/create');

			$f = $twitter->call('friendships/exists',array('user_a'=>'php_twient','user_b'=>'makotokw'));
			$this->assertEquals($f, true, 'friendships/exists');

			$f = $twitter->call('friendships/show',array('source_screen_name'=>'php_twient','target_screen_name'=>'makotokw'));
			$this->assertEquals(@$f['relationship']['target']['screen_name'],'makotokw','friendships/show');

		} catch (Exception $e) {
			$this->fail($e->getMessage());
		} catch (\Exception $e) {
			$this->fail($e->getMessage());
		}
	}

	/**
	 * @group users
	 */
	public function testUsers()
	{
		try {
			$twitter = $this->twitter;

			$u = $twitter->call('users/show',array('id'=>'makotokw'));
			$this->assertEquals($u['name'], 'Makoto Kawasaki', 'users/show');

			$u = $twitter->call('users/search',array('q'=>'makotokw'));
			$this->assertTrue(count($u)>0, 'users/search');

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
			$this->assertTrue(count($categories) > 5,'users/suggestions');

			$s = $twitter->call('users/suggestions/:slug',array('slug'=>'twitter'));
			$this->assertTrue(count($s['users']) > 1,'users/suggestions/:slug');

			$users = $twitter->call('users/suggestions/:slug/members',array('slug'=>'twitter'));
			$this->assertTrue(count($users) > 1,'users/suggestions/:slug/members');
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

			$r = $twitter->call('trends/:woeid',array('woeid'=>2487956));
			$this->assertTrue(count($r[0]['trends']) > 0,'trends/locations');

			$r = $twitter->call('trends/available');
			$this->assertTrue(isset($r[0]['woeid']),'trends/available');

			$r = $twitter->call('trends/daily');
			$this->assertTrue(count($r['trends']) > 0,'trends/daily');

			$r = $twitter->call('trends/weekly');
			$this->assertTrue(count($r['trends']) > 0,'trends/weekly');
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
			$r = $twitter->call('search',array('q'=>'Sushi'));
			$this->assertTrue(count($r['results']) > 0);
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
		$this->_count = 0;
		$twitter = $this->twitter;
		$this->assertTrue($twitter->streaming(
			'statuses/filter',
			array('track'=>'Sushi,Japan'),
			array($this, 'streamingCallback'),
			'statuses/filter')>0);
		$this->_count = 0;
		$this->assertTrue($twitter->streaming(
			'statuses/sample',
			array(),
			array($this, 'streamingFilterCallback'),
			'statuses/filter')>0);
	}

	function streamingCallback($twitter, $status)
	{
		if (isset($status['text'])) {
			echo $status['user']['name'].':'.$status['text'] . PHP_EOL;
		}
		return ($this->_count++<5);
	}

	function streamingFilterCallback($twitter, $status)
	{
		if (isset($status['text'])) {
			if(preg_match('/[ァ-ヶーぁ-ん]/u',$status['text'])) {
				echo $status['user']['name'].':'.$status['text'] . PHP_EOL;
				$this->_count++;
			}
		}
		return ($this->_count<5);
	}
}
