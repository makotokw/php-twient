<?php

namespace Twient\Auth\OAuth;

class PLAINTEXTSignatureMethod extends SignatureMethod
{
    public function getName()
    {
        return 'PLAINTEXT';
    }

    public function buildSignature($request, $consumer, $token)
    {
        $sig = array(
            Util::urlencodeRfc3986($consumer->secret)
        );

        if ($token) {
            array_push($sig, Util::urlencodeRfc3986($token->secret));
        } else {
            array_push($sig, '');
        }

        $raw = implode("&", $sig);
        // for debug purposes
        $request->baseString = $raw;

        return Util::urlencodeRfc3986($raw);
    }
}
