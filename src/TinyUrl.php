<?php
/**
 * TinyUrl class
 * This file is part of the makotokw\Twient package.
 *
 * @author     Makoto Kawasaki <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace makotokw\Twient;

class TinyUrl
{
    const URL = 'http://tinyurl.com/api-create.php';

    /**
     * Creates tiny url
     * @param string $url
     * @return string tiny url
     */
    public static function create($url)
    {
        $request = new \makotokw\Twient\Request\BaseRequest();
        return $request->get(self::URL, array('url' => $url));
    }
}
