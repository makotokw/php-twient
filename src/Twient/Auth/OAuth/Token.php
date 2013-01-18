<?php

namespace Twient\Auth\OAuth;

class Token
{
    // access tokens and request tokens
    public $key;
    public $secret;

    /**
     * key = the token
     * secret = the token secret
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * generates the basic string serialization of a token that a server
     * would respond to request_token and access_token calls with
     */
    public function __toString()
    {
        return "oauth_token=" .
            Util::urlencodeRfc3986($this->key) .
            "&oauth_token_secret=" .
            Util::urlencodeRfc3986($this->secret);
    }
}
