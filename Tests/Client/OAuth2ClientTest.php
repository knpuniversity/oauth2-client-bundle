<?php

namespace KnpU\OAuth2ClientBundle\Tests\DependencyInjection;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;

class OAuth2ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testRedirectWithState()
    {
        $requestStack = $this->prophesize('Symfony\Component\HttpFoundation\RequestStack');
        $request = $this->prophesize('Symfony\Component\HttpFoundation\Request');
        $session = $this->prophesize('Symfony\Component\HttpFoundation\Session\SessionInterface');

        $requestStack->getCurrentRequest()
            ->willReturn($request->reveal());

        $request->getSession()
            ->willReturn($session->reveal());

        $provider = $this->prophesize('League\OAuth2\Client\Provider\AbstractProvider');
        $provider->getAuthorizationUrl(['scopes' => ['scope1', 'scope2']])
            ->willReturn('http://coolOAuthServer.com/authorize');
        $provider->getState()
            ->willReturn('SOME_RANDOM_STATE');

        $session->set(OAuth2Client::OAUTH2_SESSION_STATE_KEY, 'SOME_RANDOM_STATE')
            ->shouldBeCalled();

        $client = new OAuth2Client(
            $provider->reveal(),
            $requestStack->reveal()
        );

        $response = $client->redirect(['scope1', 'scope2']);
        $this->assertInstanceOf(
            'Symfony\Component\HttpFoundation\RedirectResponse',
            $response
        );
        $this->assertEquals(
            'http://coolOAuthServer.com/authorize',
            $response->getTargetUrl()
        );
    }

    public function testRedirectWithoutState()
    {
        $requestStack = $this->prophesize('Symfony\Component\HttpFoundation\RequestStack');

        $requestStack->getCurrentRequest()
            ->shouldNotBeCalled();

        $provider = $this->prophesize('League\OAuth2\Client\Provider\AbstractProvider');
        $provider->getAuthorizationUrl([])
            ->willReturn('http://example.com');

        $client = new OAuth2Client(
            $provider->reveal(),
            $requestStack->reveal()
        );
        $client->setAsStateless();

        $response = $client->redirect();
        // don't need other checks - the fact that it didn't fail
        // by asking for the request and session is enough
        $this->assertInstanceOf(
            'Symfony\Component\HttpFoundation\RedirectResponse',
            $response
        );
    }
}
