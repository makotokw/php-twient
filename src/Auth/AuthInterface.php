<?php
/**
 * Auth\AuthInterface interface
 * This file is part of the makotokw\Twient package.
 *
 * @author     Makoto Kawasaki <makoto.kw@gmail.com>
 * @license    The MIT License
 */

namespace makotokw\Twient\Auth;

interface AuthInterface
{
    public function sign(array $data);
}
