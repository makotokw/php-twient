<?php
/**
 * Request\StreamingRequest class
 * This file is part of the makotokw\Twient package.
 *
 * @author     Makoto Kawasaki <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace makotokw\Twient\Request;

class StreamingRequest extends BaseRequest
{
    /**
     * @param $stream
     * @param $callback
     * @return int
     */
    protected function doStreaming($stream, $callback)
    {
        $count = 0;
        $core = $this->getCore();
        if ($stream) {
            while ($data = fgets($stream)) {
                $count++;
                $status = json_decode($data, $this->assoc);
                if (!call_user_func($callback, $core, $status)) {
                    break;
                }
            }
        }
        return $count;
    }

    /**
     * @param $url
     * @param array $params
     * @param \makotokw\Twient\Auth\AuthInterface $auth
     * @param $callback
     * @return int|mixed
     */
    public function getJSON($url, array $params = array(), $auth = null, $callback = null)
    {
        $method = 'GET';
        $headers = array(
            'User-Agent: ' . $this->getUserAgent(),
        );

        $signedData = $auth->sign(array('url' => $url, 'method' => $method, 'params' => $params));
        $url = $signedData['url'];
        if (isset($signedData['headers'])) {
            $headers = array_merge($headers, $signedData['headers']);
        }
        $opt = array(
            'http' => array(
                'method' => 'GET',
                'protocol_version' => 1.1,
                'header' => implode("\r\n", $headers),
            )
        );
        return $this->doStreaming(fopen($url, 'r', false, stream_context_create($opt)), $callback);
    }

    /**
     * @param $url
     * @param array $params
     * @param \makotokw\Twient\Auth\AuthInterface $auth
     * @param null $callback
     * @return int|mixed
     */
    public function postJSON($url, array $params = array(), $auth = null, $callback = null)
    {
        $method = 'POST';
        $headers = array(
            'User-Agent: ' . $this->getUserAgent(),
        );

        $signedData = $auth->sign(array('url' => $url, 'method' => $method, 'params' => $params));
        $url = $signedData['url'];
        $content = $signedData['post_data'];
        if (isset($signedData['headers'])) {
            $headers = array_merge($headers, $signedData['headers']);
        }

        $headers = array_merge(
            $headers,
            array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($content),
            )
        );

        $opt = array(
            'http' => array(
                'method' => $method,
                'protocol_version' => 1.1,
                'header' => implode("\r\n", $headers),
                'content' => $content
            )
        );
        return $this->doStreaming(fopen($url, 'r', false, stream_context_create($opt)), $callback);
    }
}
