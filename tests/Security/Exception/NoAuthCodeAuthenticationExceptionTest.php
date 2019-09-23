<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\tests\Security\Exception;

use KnpU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;
use PHPUnit\Framework\TestCase;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class NoAuthCodeAuthenticationExceptionTest extends TestCase
{
    public function testException()
    {
        $e = new NoAuthCodeAuthenticationException();

        if (method_exists($this, 'assertIsString')) {
            $this->assertIsString($e->getMessageKey());
        } else {
            $this->assertInternalType('string', $e->getMessageKey());
        }
    }
}
