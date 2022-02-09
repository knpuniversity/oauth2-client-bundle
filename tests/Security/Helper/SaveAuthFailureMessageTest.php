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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SaveAuthFailureMessageTest extends TestCase
{
    use HelperTestMockBuilderTrait;

    public function testShouldThrowExceptionIfSessionDoesNotExist()
    {
        $request = $this->createRequest(false);
        $mockAuthException = new AuthenticationException();

        $testFailureMessage = new SaveAuthFailureMessageTester();

        $this->expectException(\LogicException::class);
        $testFailureMessage->callSaveAuthenticationErrorToSession($request, $mockAuthException);
    }

    public function testShouldUpdateSessionErrorIfSessionExists()
    {
        $request = $this->createRequest();

        $mockAuthException = new AuthenticationException();

        $testFailureMessage = new SaveAuthFailureMessageTester();

        $testFailureMessage->callSaveAuthenticationErrorToSession($request, $mockAuthException);
        $session = $request->getSession();
        $this->assertInstanceOf(AuthenticationException::class, $session->get(Security::AUTHENTICATION_ERROR));
    }
}

class SaveAuthFailureMessageTester
{
    use SaveAuthFailureMessage;

    public function callSaveAuthenticationErrorToSession($request, $exception): void
    {
        $this->saveAuthenticationErrorToSession($request, $exception);
    }
}
