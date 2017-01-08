<?php

namespace KnpU\OAuth2ClientBundle\Tests\Security\Exception;

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
