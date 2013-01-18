<?php

namespace Twient\Auth\OAuth;

use Twient\Exception;

class Server
{
    protected $timestampThreshold = 300; // in seconds, five minutes
    protected $version = 1.0; // hi blaine
    protected $signatureMethods = array();

    /**
     * @var DataStoreInterface
     */
    protected $dataStore;

    /**
     * @param DataStoreInterface $dataStore
     */
    public function __construct($dataStore)
    {
        $this->dataStore = $dataStore;
    }

    /**
     * @param SignatureMethod $signatureMethod
     */
    public function addSignatureMethod($signatureMethod)
    {
        $this->signatureMethods[$signatureMethod->getName()] = $signatureMethod;
    }

    // high level functions

    /**
     * process a request_token request
     * returns the request token on success
     * @param Request $request
     * @return mixed
     */
    public function fetchRequestToken($request)
    {
        $this->getVersion($request);

        $consumer = $this->getConsumer($request);

        // no token required for the initial token request
        $token = null;

        $this->checkSignature($request, $consumer, $token);

        $new_token = $this->dataStore->newRequestToken($consumer);

        return $new_token;
    }

    /**
     * process an access_token request
     * returns the access token on success
     * @param Request $request
     * @return mixed
     */
    public function fetchAccessToken($request)
    {
        $this->getVersion($request);

        $consumer = $this->getConsumer($request);

        // requires authorized request token
        $token = $this->getToken($request, $consumer, "request");

        $this->checkSignature($request, $consumer, $token);

        $newToken = $this->dataStore->newAccessToken($token, $consumer);

        return $newToken;
    }

    /**
     * verify an api call, checks all the parameters
     * @param Request $request
     * @return array
     */
    public function verifyRequest($request)
    {
        $this->getVersion($request);
        $consumer = $this->getConsumer($request);
        $token = $this->getToken($request, $consumer, "access");
        $this->checkSignature($request, $consumer, $token);
        return array($consumer, $token);
    }

    // Internals from here
    /**
     * version 1
     * @param Request $request
     * @return float
     * @throws \Twient\Exception
     */
    private function getVersion($request)
    {
        $version = $request->getParameter("oauth_version");
        if (!$version) {
            $version = 1.0;
        }
        if ($version && $version != $this->version) {
            throw new Exception("OAuth version '$version' not supported");
        }
        return $version;
    }

    /**
     * figure out the signature with some defaults
     * @param Request $request
     * @return SignatureMethod
     * @throws \Twient\Exception
     */
    private function getSignatureMethod(&$request)
    {
        $signatureMethod = @$request->getParameter("oauth_signature_method");
        if (!$signatureMethod) {
            $signatureMethod = "PLAINTEXT";
        }
        if (!in_array(
            $signatureMethod,
            array_keys($this->signatureMethods)
        )
        ) {
            throw new Exception(
                "Signature method '$signatureMethod' not supported " .
                "try one of the following: " .
                implode(", ", array_keys($this->signatureMethods))
            );
        }
        return $this->signatureMethods[$signatureMethod];
    }

    /**
     * try to find the consumer for the provided request's consumer key
     * @param Request $request
     * @return mixed
     * @throws \Twient\Exception
     */
    private function getConsumer($request)
    {
        $consumerKey = @$request->getParameter("oauth_consumer_key");
        if (!$consumerKey) {
            throw new Exception("Invalid consumer key");
        }

        $consumer = $this->dataStore->lookupConsumer($consumerKey);
        if (!$consumer) {
            throw new Exception("Invalid consumer");
        }

        return $consumer;
    }

    /**
     * try to find the token for the provided request's token key
     * @param Request $request
     * @param $consumer
     * @param string $token_type
     * @return mixed
     * @throws \Twient\Exception
     */
    private function getToken($request, $consumer, $token_type = "access")
    {
        $token_field = @$request->getParameter('oauth_token');
        $token = $this->dataStore->lookupToken(
            $consumer,
            $token_type,
            $token_field
        );
        if (!$token) {
            throw new Exception("Invalid $token_type token: $token_field");
        }
        return $token;
    }

    /**
     * all-in-one function to check the signature on a request
     * should guess the signature method appropriately
     * @param Request $request
     * @param $consumer
     * @param $token
     * @throws \Twient\Exception
     */
    private function checkSignature($request, $consumer, $token)
    {
        // this should probably be in a different method
        $timestamp = @$request->getParameter('oauth_timestamp');
        $nonce = @$request->getParameter('oauth_nonce');

        $this->checkTimestamp($timestamp);
        $this->checkNonce($consumer, $token, $nonce, $timestamp);

        $signatureMethod = $this->getSignatureMethod($request);

        $signature = $request->getParameter('oauth_signature');
        $valid_sig = $signatureMethod->checkSignature(
            $request,
            $consumer,
            $token,
            $signature
        );

        if (!$valid_sig) {
            throw new Exception("Invalid signature");
        }
    }

    /**
     * check that the timestamp is new enough
     * @param $timestamp
     * @throws \Twient\Exception
     */
    private function checkTimestamp($timestamp)
    {
        // verify that timestamp is recentish
        $now = time();
        if ($now - $timestamp > $this->timestampThreshold) {
            throw new Exception(
                "Expired timestamp, yours $timestamp, ours $now"
            );
        }
    }

    /**
     * check that the nonce is not repeated
     * @param $consumer
     * @param $token
     * @param $nonce
     * @param $timestamp
     * @throws \Twient\Exception
     */
    private function checkNonce($consumer, $token, $nonce, $timestamp)
    {
        // verify that the nonce is uniqueish
        $found = $this->dataStore->lookupNonce(
            $consumer,
            $token,
            $nonce,
            $timestamp
        );
        if ($found) {
            throw new Exception("Nonce already used: $nonce");
        }
    }
}
