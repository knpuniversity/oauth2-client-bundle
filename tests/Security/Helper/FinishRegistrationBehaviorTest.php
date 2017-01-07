<?php
/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 * Date: 07/01/2017
 * Time: 23:29
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Helper;


use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;

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
        $request->getSession()->willReturn(new StubSession());
        $res = $this->traitObject->getUserInfoFromSession($request->reveal());

        $this->assertEquals($res, true);
    }

}

class StubSession
{
    public function get()
    {
        return true;
    }
}