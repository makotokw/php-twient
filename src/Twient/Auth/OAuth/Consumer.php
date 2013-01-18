<?php

namespace Twient\Auth\OAuth;

class Consumer
{
    public $key;
    public $secret;
    public $callbackUrl;

    public function __construct($key, $secret, $callbackUrl = null)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callbackUrl = $callbackUrl;
    }

    public function __toString()
    {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }
}
