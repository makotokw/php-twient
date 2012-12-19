<?php
/**
 * Twient\Auth\AuthInterface interface
 * This file is part of the Twient package.
 * 
 * @author     makoto_kw <makoto.kw@gmail.com>
 * @license    New BSD License, http://www.opensource.org/licenses/bsd-license.php
 */

namespace Twient\Auth;

interface AuthInterface
{
	public function sign(array $data);
}