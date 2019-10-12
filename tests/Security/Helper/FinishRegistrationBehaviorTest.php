<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\tests\Security\Helper;

use LogicException;
use Symfony\Component\HttpFoundation\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class FinishRegistrationBehaviorTest extends TestCase
{
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
}
