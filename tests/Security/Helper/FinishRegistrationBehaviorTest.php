<?php

namespace KnpU\OAuth2ClientBundle\Tests\Security\Helper;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class FinishRegistrationBehaviorTest extends \PHPUnit_Framework_TestCase
{
    private $traitObject;

    public function setUp()
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
        $request->getSession()->willReturn($session->reveal());
        $userInfo = $this->traitObject->getUserInfoFromSession($request->reveal());

        $this->assertEquals($userInfo, ['username' => 'some_user_info']);
    }
}
