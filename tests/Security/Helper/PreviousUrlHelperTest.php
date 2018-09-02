<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\tests\Security\Helper;

use Symfony\Component\HttpFoundation\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class PreviousUrlHelperTest extends TestCase
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
        $session = $this->prophesize(Session::class);
        $session->get('_security.some_firewall_name.target_path')
            ->willReturn('/some/url');

        $request->getSession()->willReturn($session->reveal());
        $previousUrl = $this->traitObject->getPreviousUrl(
            $request->reveal(),
            'some_firewall_name'
        );

        $this->assertEquals($previousUrl, '/some/url');
    }
}
