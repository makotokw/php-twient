<?php

namespace Twient\Auth\OAuth;

interface DataStoreInterface
{
    public function lookupConsumer($consumerKey);

    public function lookupToken($consumer, $tokenType, $token);

    public function lookupNonce($consumer, $token, $nonce, $timestamp);

    public function newRequestToken($consumer);

    public function newAccessToken($token, $consumer);
}
