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

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class NoAuthCodeAuthenticationExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $e = new NoAuthCodeAuthenticationException();

        $this->assertInternalType('string', $e->getMessageKey());
    }
}
