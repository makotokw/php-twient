<?php

namespace Twient\Auth\OAuth;

interface DataStoreInterface
{
	function lookupConsumer($consumerKey);
	function lookupToken($consumer, $tokenType, $token);
	function lookupNonce($consumer, $token, $nonce, $timestamp);
	function newRequestToken($consumer);
	function newAccessToken($token, $consumer);
}
