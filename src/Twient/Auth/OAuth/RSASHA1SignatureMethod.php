<?php

namespace Twient\Auth\OAuth;

use Twient\Exception;

class RSASHA1SignatureMethod extends SignatureMethod
{
    public function getName()
    {
        return "RSA-SHA1";
    }

    /**
     * @param Request $request
     * @throws \Twient\Exception
     */
    protected function fetchPublicCert($request)
    {
        // not implemented yet, ideas are:
        // (1) do a lookup in a table of trusted certs keyed off of consumer
        // (2) fetch via http using a url provided by the requester
        // (3) some sort of specific discovery code based on request
        //
        // either way should return a string representation of the certificate
        throw new Exception("fetch_public_cert not implemented");
    }

    /**
     * @param Request $request
     * @throws \Twient\Exception
     */
    protected function fetchPrivateCert($request)
    {
        // not implemented yet, ideas are:
        // (1) do a lookup in a table of trusted certs keyed off of consumer
        //
        // either way should return a string representation of the certificate
        throw new Exception("fetch_private_cert not implemented");
    }

    public function buildSignature($request, $consumer, $token)
    {
        $base_string = $request->getSignatureBaseString();
        $request->baseString = $base_string;

        // Fetch the private key cert based on the request
        $cert = $this->fetchPrivateCert($request);

        // Pull the private key ID from the certificate
        $privateKey = openssl_get_privatekey($cert);

        // Sign using the key
        $ok = openssl_sign($base_string, $signature, $privateKey);

        // Release the key resource
        openssl_free_key($privateKey);

        return base64_encode($signature);
    }

    public function checkSignature($request, $consumer, $token, $signature)
    {
        $decoded_sig = base64_decode($signature);

        $base_string = $request->getSignatureBaseString();

        // Fetch the public key cert based on the request
        $cert = $this->fetchPublicCert($request);

        // Pull the public key ID from the certificate
        $publicKey = openssl_get_publickey($cert);

        // Check the computed signature against the one passed in the query
        $ok = openssl_verify($base_string, $decoded_sig, $publicKey);

        // Release the key resource
        openssl_free_key($publicKey);

        return $ok == 1;
    }
}
