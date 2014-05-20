<?php
/**
 * Auth\AuthInterface interface
 * This file is part of the Makotokw\Twient package.
 *
 * @author     Makoto Kawasaki <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace Makotokw\Twient\Auth;

interface AuthInterface
{
    public function sign(array $data);
}
