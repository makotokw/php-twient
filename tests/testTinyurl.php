<?php
require_once dirname(__FILE__).'/lime/lime.php';
require_once dirname(__FILE__).'/../lib/Twitter.php';
$t = new lime_test(1, new lime_output_color());
$t->is(Twitter_TinyUrl::create('http://twitter.com/'),'http://tinyurl.com/y4lug8','Twitter_TinyUrl::create');
