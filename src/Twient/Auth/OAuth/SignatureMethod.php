<?php

namespace Twient\Auth\OAuth;

class SignatureMethod
{
    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * @param Request $request
     * @param Consumer $consumer
     * @param Token $token
     * @return string
     */
    public function buildSignature($request, $consumer, $token)
    {
        return '';
    }

    /**
     * @param Request $request
     * @param Consumer $consumer
     * @param Token $token
     * @param string $signature
     * @return bool
     */
    public function checkSignature($request, $consumer, $token, $signature)
    {
        $built = $this->buildSignature($request, $consumer, $token);
        return $built == $signature;
    }
}
