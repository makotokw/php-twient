<?php

namespace Twient\Auth\OAuth;

class HMACSHA1SignatureMethod extends SignatureMethod
{
    public function getName()
    {
        return 'HMAC-SHA1';
    }

    public function buildSignature($request, $consumer, $token)
    {
        $base_string = $request->getSignatureBaseString();
        $request->baseString = $base_string;

        $key_parts = array(
            $consumer->secret,
            ($token) ? $token->secret : ''
        );

        $key_parts = Util::urlencodeRfc3986($key_parts);
        $key = implode('&', $key_parts);

        return base64_encode(hash_hmac('sha1', $base_string, $key, true));
    }
}
