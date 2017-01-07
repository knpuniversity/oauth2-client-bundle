<?php
/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 * Date: 07/01/2017
 * Time: 23:59
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Helper;


use KnpU\OAuth2ClientBundle\Security\Helper\PreviousUrlHelper;


class PreviousUrlHelperTest extends \PHPUnit_Framework_TestCase
{
    private $traitObject;

    public function setUp()
    {
        $this->traitObject = $this
            ->getMockForTrait('KnpU\OAuth2ClientBundle\Security\Helper\PreviousUrlHelper');
    }

    public function testGetPreviousUrl()
    {
        $request = $this->prophesize('Symfony\Component\HttpFoundation\Request');
        $request->getSession()->willReturn(new StubSession());
        $res = $this->traitObject->getPreviousUrl($request->reveal(), '');

        $this->assertEquals($res, true);
    }
}
