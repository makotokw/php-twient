<?php
/**
 * Twient\Request\CurlRequest class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Twient\Request;

class CurlRequest extends BaseRequest
{
    protected function doRequest($url, $method, $headers = array(), $postData = null)
    {
        if (!function_exists('curl_init')) {
            throw new \Twient\Exception('php_curl is not found');
        }

        $ci = curl_init();

        $headers = array_merge(
            $headers,
            array(
                // disable Expect: 100-continue
                // cURL add 100-continue when postData is over 1024 chars
                'Expect:',
                'User-Agent: ' . $this->getUserAgent(),
            )
        );

        curl_setopt($ci, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->getTimeout());
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_HEADER, false);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postData)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postData);
                }
                break;
        }

        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        $httpCode = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        curl_close($ci);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \Twient\Exception(null, $httpCode, $response);
        }

        return $response;
    }
}
