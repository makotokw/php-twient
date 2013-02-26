<?php
/**
 * Twient\TinyUrl class
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Twient;

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
        $request = new \Twient\Request\BaseRequest();
        return $request->get(self::URL, array('url' => $url));
    }
}
