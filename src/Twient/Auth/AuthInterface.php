<?php
/**
 * Twient\Auth\AuthInterface interface
 * This file is part of the Twient package.
 *
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Twient\Auth;

interface AuthInterface
{
    public function sign(array $data);
}
