<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Helper;

use KnpU\OAuth2ClientBundle\Security\Helper\SaveAuthFailureMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SaveAuthFailureMessageTest extends TestCase
{
    public function testShouldThrowExceptionIfSessionDoesNotExist()
    {
        $mockRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockRequest->method("hasSession")->willReturn(false);
        $mockAuthException = new AuthenticationException();

        $testFailureMessage = new SaveAuthFailureMessageTester();

        $this->expectException(\LogicException::class);
        $testFailureMessage->callSaveAuthenticationErrorToSession($mockRequest, $mockAuthException);
    }

    public function testShouldThrowExceptionIfExistsButIsNotSessionInterface()
    {
        $mockRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockRequest->method("hasSession")->willReturn(true);
        $mockRequest->method("getSession")->willReturn(\stdClass::class);
        $mockAuthException = new AuthenticationException();

        $testFailureMessage = new SaveAuthFailureMessageTester();

        $this->expectException(\LogicException::class);
        $testFailureMessage->callSaveAuthenticationErrorToSession($mockRequest, $mockAuthException);
    }

    public function testShouldUpdateSessionErrorIfSessionExists()
    {
        $mockSession = $this->getMockBuilder(SessionInterface::class)->getMock();
        $mockRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockRequest->method("hasSession")->willReturn(true);
        $mockRequest->method("getSession")->willReturn($mockSession);
        $mockAuthException = new AuthenticationException();

        $testFailureMessage = new SaveAuthFailureMessageTester();

        $mockSession->expects($this->once())->method("set")
            ->with(
                $this->equalTo('_security.last_error'),
                $this->isInstanceOf(AuthenticationException::class)
            );
        $testFailureMessage->callSaveAuthenticationErrorToSession($mockRequest, $mockAuthException);
    }
}

class SaveAuthFailureMessageTester
{
    use SaveAuthFailureMessage;
    public function callSaveAuthenticationErrorToSession(Request $request, AuthenticationException $exception): void
    {
        $this->saveAuthenticationErrorToSession($request, $exception);
    }
}
