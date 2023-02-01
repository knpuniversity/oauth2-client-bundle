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
            ->getMockForTrait(FinishRegistrationBehavior::class);
    }

    public function testGetUserInfoFromSession()
    {
        $request = $this->createRequest();
        $request->getSession()->set('guard.finish_registration.user_information', ['username' => 'some_user_info']);

        $userInfo = $this->traitObject->getUserInfoFromSession($request);

        $this->assertEquals($userInfo, ['username' => 'some_user_info']);
    }

    public function testGetUserInfoFromSessionWithoutSession()
    {
        $request = $this->createRequest(false);

        $this->expectException(LogicException::class);
        $this->traitObject->getUserInfoFromSession($request);
    }

    public function testShouldThrowExceptionIfSessionDoesNotExist()
    {
        $request = $this->createRequest(false);
        $mockAuthException = new FinishRegistrationException(['id' => '1', 'name' => 'testUser']);

        $testFailureMessage = new FinishRegistrationBehaviorTester();

        $this->expectException(\LogicException::class);
        $testFailureMessage->callSaveUserInfoToSession($request, $mockAuthException);
    }

    public function testShouldUpdateSessionDataIfSessionExists()
    {
        $request = $this->createRequest();

        $userInfo = ['id' => '1', 'name' => 'testUser'];
        $mockAuthException = new FinishRegistrationException($userInfo);

        $testFailureMessage = new FinishRegistrationBehaviorTester();

        $session = $request->getSession();
        $testFailureMessage->callSaveUserInfoToSession($request, $mockAuthException);
        $this->assertSame($userInfo, $session->get('guard.finish_registration.user_information'));
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
