<?php
/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 * Date: 07/01/2017
 * Time: 23:27
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Exception;


use KnpU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;


class NoAuthCodeAuthenticationExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testException()
    {
        $e = new NoAuthCodeAuthenticationException();

        $this->assertInternalType('string', $e->getMessageKey());
    }
}
