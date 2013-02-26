<?php

namespace Twient;

class TinyUrlTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $this->assertEquals(TinyUrl::create('http://twitter.com/'), 'http://tinyurl.com/y4lug8');
    }
}