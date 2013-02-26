<?php
/**
 * Twient\Auth\BasicAuth class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Twient\Auth;

class BasicAuth implements AuthInterface
{
    protected $username;
    protected $password;

    public function __construct($username = null, $password = null)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function sign(array $data)
    {
        $signedData = $data;
        $signedData['headers'] = array(
            'Authorization: Basic ' . base64_encode(
                $this->username . ':' . $this->password
            )
        );
        $method = strtolower($data['method']);
        switch ($method) {
            case 'get':
                $signedData['url'] .= '?' . http_build_query($data['params']);
                break;
            default:
                $signedData['post_data'] = http_build_query($data['params']);
                break;
        }
        return $signedData;
    }
}
