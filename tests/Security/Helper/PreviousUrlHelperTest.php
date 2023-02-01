<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Helper;

use KnpU\OAuth2ClientBundle\Security\Helper\PreviousUrlHelper;
use PHPUnit\Framework\TestCase;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class PreviousUrlHelperTest extends TestCase
{
    use HelperTestMockBuilderTrait;
    private $traitObject;

    public function setUp(): void
    {
        $this->traitObject = $this
            ->getMockForTrait(PreviousUrlHelper::class);
    }

    public function testGetPreviousUrl()
    {
        $request = $this->createRequest();
        $session = $request->getSession();
        $session->set('_security.some_firewall_name.target_path', '/some/url');

        $previousUrl = $this->traitObject->getPreviousUrl(
            $request,
            'some_firewall_name'
        );

        $this->assertEquals($previousUrl, '/some/url');
    }

    public function testGetPreviousUrlWithoutSession()
    {
        $request = $this->createRequest(false);

        $previousUrl = $this->traitObject->getPreviousUrl(
            $request,
            'some_firewall_name'
        );

        $this->assertEquals($previousUrl, '');
    }
}
