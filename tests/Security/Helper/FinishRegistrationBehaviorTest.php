<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Helper;

use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;
use KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use LogicException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class FinishRegistrationBehaviorTest extends TestCase
{
    use HelperTestMockBuilderTrait;
    private $traitObject;

    public function setUp(): void
    {
        $this->traitObject = $this
            ->getMockForTrait('KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior');
    }

    public function testGetUserInfoFromSession()
    {
        $request = $this->prophesize('Symfony\Component\HttpFoundation\Request');
        $session = $this->prophesize(Session::class);
        $session->get('guard.finish_registration.user_information')
            ->willReturn(['username' => 'some_user_info']);
        $request->hasSession()->willReturn(true);
        $request->getSession()->willReturn($session->reveal());

        $userInfo = $this->traitObject->getUserInfoFromSession($request->reveal());

        $this->assertEquals($userInfo, ['username' => 'some_user_info']);
    }

    public function testGetUserInfoFromSessionWithoutSession()
    {
        $request = $this->prophesize('Symfony\Component\HttpFoundation\Request');
        $request->hasSession()->willReturn(false);
        //$request->getSession()->willReturn(null);

        $this->expectException(LogicException::class);
        $this->traitObject->getUserInfoFromSession($request->reveal());
    }

    public function testShouldThrowExceptionIfSessionDoesNotExist()
    {
        $mockRequest = $this->getMockRequest(false);
        $mockAuthException = new FinishRegistrationException(['id' => '1', 'name' => 'testUser']);

        $testFailureMessage = new FinishRegistrationBehaviorTester();

        $this->expectException(\LogicException::class);
        $testFailureMessage->callSaveUserInfoToSession($mockRequest, $mockAuthException);
    }

    public function testShouldThrowExceptionIfSessionExistsButNotSessionInterface()
    {
        $mockRequest = $this->getMockRequest(true);

        $mockAuthException = new FinishRegistrationException(['id' => '1', 'name' => 'testUser']);

        $testFailureMessage = new FinishRegistrationBehaviorTester();

        $this->expectException(\LogicException::class);
        $testFailureMessage->callSaveUserInfoToSession($mockRequest, $mockAuthException);
    }

    public function testShouldUpdateSessionDataIfSessionExists()
    {
        $mockSession = $this->getMockBuilder(Session::class)->getMock();
        $mockRequest = $this->getMockRequest(true, $mockSession);

        $userInfo = ['id' => '1', 'name' => 'testUser'];
        $mockAuthException = new FinishRegistrationException($userInfo);

        $testFailureMessage = new FinishRegistrationBehaviorTester();

        $mockSession->expects($this->once())->method("set")
            ->with(
                $this->equalTo('guard.finish_registration.user_information'),
                $this->equalTo($userInfo)
            );
        $testFailureMessage->callSaveUserInfoToSession($mockRequest, $mockAuthException);
    }
}

class FinishRegistrationBehaviorTester
{
    use FinishRegistrationBehavior;
    public function callSaveUserInfoToSession($request, $exception): void
    {
        $this->saveUserInfoToSession($request, $exception);
    }
}
