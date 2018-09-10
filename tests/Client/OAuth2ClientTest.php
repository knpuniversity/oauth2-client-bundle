<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\tests\Client;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use PHPUnit\Framework\TestCase;

class OAuth2ClientTest extends TestCase
{
    private $requestStack;
    /** @var Request */
    private $request;
    private $session;
    private $provider;

    public function setup()
    {
        $this->requestStack = new RequestStack();
        $this->session = $this->prophesize('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $this->provider = $this->prophesize('League\OAuth2\Client\Provider\AbstractProvider');

        $this->request = new Request();
        $this->request->setSession($this->session->reveal());

        $this->requestStack->push($this->request);
    }

    public function testRedirectWithState()
    {
        $this->provider->getAuthorizationUrl(['scope' => ['scope1', 'scope2']])
            ->willReturn('http://coolOAuthServer.com/authorize');
        $this->provider->getState()
            ->willReturn('SOME_RANDOM_STATE');

        $this->session->set(OAuth2Client::OAUTH2_SESSION_STATE_KEY, 'SOME_RANDOM_STATE')
            ->shouldBeCalled();

        $client = new OAuth2Client(
            $this->provider->reveal(),
            $this->requestStack
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

        $this->provider->getAuthorizationUrl([])
            ->willReturn('http://example.com');

        $client = new OAuth2Client(
            $this->provider->reveal(),
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

    public function testRedirectWithOptions()
    {
        $requestStack = $this->prophesize('Symfony\Component\HttpFoundation\RequestStack');

        $this->provider->getAuthorizationUrl([
            'scope' => ['scopeA'],
            'optionA' => 'FOO',
        ])
            ->willReturn('http://example.com');

        $client = new OAuth2Client(
            $this->provider->reveal(),
            $requestStack->reveal()
        );
        $client->setAsStateless();

        $response = $client->redirect(
            ['scopeA'],
            ['optionA' => 'FOO']
        );
        // don't need other checks - the assertion above when
        // mocking getAuthorizationUrl is enough
        $this->assertInstanceOf(
            'Symfony\Component\HttpFoundation\RedirectResponse',
            $response
        );
    }

    public function testGetAccessToken()
    {
        $this->request->query->set('state', 'THE_STATE');
        $this->request->query->set('code', 'CODE_ABC');

        $this->session->get(OAuth2Client::OAUTH2_SESSION_STATE_KEY)
            ->willReturn('THE_STATE');

        $expectedToken = $this->prophesize('League\OAuth2\Client\Token\AccessToken');
        $this->provider->getAccessToken('authorization_code', ['code' => 'CODE_ABC'])
            ->willReturn($expectedToken->reveal());

        $client = new OAuth2Client(
            $this->provider->reveal(),
            $this->requestStack
        );
        $actualToken = $client->getAccessToken();
        $this->assertSame($expectedToken->reveal(), $actualToken);
    }

    public function testGetAccessTokenFromPOST()
    {
        $this->request->request->set('code', 'CODE_ABC');

        $expectedToken = $this->prophesize('League\OAuth2\Client\Token\AccessToken');
        $this->provider->getAccessToken('authorization_code', ['code' => 'CODE_ABC'])
            ->willReturn($expectedToken->reveal());

        $client = new OAuth2Client(
            $this->provider->reveal(),
            $this->requestStack
        );
        $client->setAsStateless();
        $actualToken = $client->getAccessToken();
        $this->assertSame($expectedToken->reveal(), $actualToken);
    }

    /**
     * @expectedException \KnpU\OAuth2ClientBundle\Exception\InvalidStateException
     */
    public function testGetAccessTokenThrowsInvalidStateException()
    {
        $this->request->query->set('state', 'ACTUAL_STATE');
        $this->session->get(OAuth2Client::OAUTH2_SESSION_STATE_KEY)
            ->willReturn('OTHER_STATE');

        $client = new OAuth2Client(
            $this->provider->reveal(),
            $this->requestStack
        );
        $client->getAccessToken();
    }

    /**
     * @expectedException \KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException
     */
    public function testGetAccessTokenThrowsMissingAuthCodeException()
    {
        $this->request->query->set('state', 'ACTUAL_STATE');
        $this->session->get(OAuth2Client::OAUTH2_SESSION_STATE_KEY)
           ->willReturn('ACTUAL_STATE');

        // don't set a code query parameter
        $client = new OAuth2Client(
            $this->provider->reveal(),
            $this->requestStack
        );
        $client->getAccessToken();
    }

    public function testFetchUser()
    {
        $this->request->request->set('code', 'CODE_ABC');

        $expectedToken = $this->prophesize('League\OAuth2\Client\Token\AccessToken');
        $this->provider->getAccessToken('authorization_code', ['code' => 'CODE_ABC'])
            ->willReturn($expectedToken->reveal());

        $client = new OAuth2Client(
            $this->provider->reveal(),
            $this->requestStack
        );

        $client->setAsStateless();
        $actualToken = $client->getAccessToken();

        $resourceOwner = new FacebookUser([
            'id' => '1',
            'name' => 'testUser',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
        ]);

        $this->provider->getResourceOwner($actualToken)->willReturn($resourceOwner);
        $user = $client->fetchUser($actualToken);

        $this->assertInstanceOf('League\OAuth2\Client\Provider\FacebookUser', $user);
        $this->assertEquals('testUser', $user->getName());
    }
}
