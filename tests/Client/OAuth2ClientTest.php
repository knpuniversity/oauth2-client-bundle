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
use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class OAuth2ClientTest extends TestCase
{
    private RequestStack $requestStack;
    private Request $request;
    private Session $session;
    private $provider;

    public function setup(): void
    {
        $this->requestStack = new RequestStack();
        $this->session = new Session(new MockArraySessionStorage());
        $this->provider = $this->createMock(AbstractProvider::class);

        $this->request = new Request();
        $this->request->setSession($this->session);

        $this->requestStack->push($this->request);
    }

    public function testRedirectWithState()
    {
        $this->provider->method('getAuthorizationUrl')
            ->with(['scope' => ['scope1', 'scope2']])
            ->willReturn('http://coolOAuthServer.com/authorize');
        $this->provider->method('getState')
            ->willReturn('SOME_RANDOM_STATE');

        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );

        $response = $client->redirect(['scope1', 'scope2']);
        $this->assertInstanceOf(
            RedirectResponse::class,
            $response
        );
        $this->assertEquals(
            'http://coolOAuthServer.com/authorize',
            $response->getTargetUrl()
        );
        $this->assertSame('SOME_RANDOM_STATE', $this->session->get(OAuth2Client::OAUTH2_SESSION_STATE_KEY));
    }

    public function testRedirectWithoutState()
    {
        $requestStack = $this->createMock(RequestStack::class);

        $requestStack->expects($this->never())
            ->method('getCurrentRequest');

        $this->provider->method('getAuthorizationUrl')
            ->with([])
            ->willReturn('http://example.com');

        $client = new OAuth2Client(
            $this->provider,
            $requestStack
        );
        $client->setAsStateless();

        $response = $client->redirect();
        // don't need other checks - the fact that it didn't fail
        // by asking for the request and session is enough
        $this->assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    public function testRedirectWithOptions()
    {
        $this->provider->method('getAuthorizationUrl')
            ->with([
                'scope' => ['scopeA'],
                'optionA' => 'FOO',
            ])
            ->willReturn('http://example.com');

        $client = new OAuth2Client(
            $this->provider,
            new RequestStack()
        );
        $client->setAsStateless();

        $response = $client->redirect(
            ['scopeA'],
            ['optionA' => 'FOO']
        );
        // don't need other checks - the assertion above when
        // mocking getAuthorizationUrl is enough
        $this->assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    public function testGetAccessToken()
    {
        $this->request->query->set('state', 'THE_STATE');
        $this->request->query->set('code', 'CODE_ABC');

        $this->session->set(OAuth2Client::OAUTH2_SESSION_STATE_KEY, 'THE_STATE');

        $expectedToken = new AccessToken(['access_token' => 'foo']);
        $this->provider->method('getAccessToken')
            ->with('authorization_code', ['code' => 'CODE_ABC'])
            ->willReturn($expectedToken);

        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );
        $this->assertSame($expectedToken, $client->getAccessToken());
    }

    public function testGetAccessTokenWithOptions()
    {
        $this->request->query->set('state', 'THE_STATE');
        $this->request->query->set('code', 'CODE_ABC');

        $this->session->set(OAuth2Client::OAUTH2_SESSION_STATE_KEY, 'THE_STATE');

        $expectedToken = new AccessToken(['access_token' => 'foo']);
        $this->provider->method('getAccessToken')
            ->with('authorization_code', ['code' => 'CODE_ABC', 'redirect_uri' => 'https://some.url'])
            ->willReturn($expectedToken);

        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );
        $actualToken = $client->getAccessToken(['redirect_uri' => 'https://some.url']);
        $this->assertSame($expectedToken, $actualToken);
    }

    public function testGetAccessTokenFromPOST()
    {
        $this->request->request->set('code', 'CODE_ABC');

        $expectedToken = new AccessToken(['access_token' => 'foo']);
        $this->provider->method('getAccessToken')
            ->with('authorization_code', ['code' => 'CODE_ABC'])
            ->willReturn($expectedToken);

        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );
        $client->setAsStateless();
        $this->assertSame($expectedToken, $client->getAccessToken());
    }

    public function testRefreshAccessToken()
    {
        $existingToken = new AccessToken([
            'access_token' => 'existing',
            'refresh_token' => 'TOKEN_ABC',
        ]);

        $expectedToken = new AccessToken(['access_token' => 'new_one']);
        $this->provider->method('getAccessToken')
            ->with('refresh_token', ['refresh_token' => 'TOKEN_ABC'])
            ->willReturn($expectedToken);

        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );
        $actualToken = $client->refreshAccessToken($existingToken->getRefreshToken());
        $this->assertSame($expectedToken, $actualToken);
    }

    public function testRefreshAccessTokenWithOptions()
    {
        $existingToken = new AccessToken([
            'access_token' => 'existing',
            'refresh_token' => 'TOKEN_ABC',
        ]);

        $expectedToken = new AccessToken(['access_token' => 'new_one']);
        $this->provider->method('getAccessToken')
            ->with('refresh_token', ['refresh_token' => 'TOKEN_ABC', 'redirect_uri' => 'https://some.url'])
            ->willReturn($expectedToken);

        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );
        $actualToken = $client->refreshAccessToken($existingToken->getRefreshToken(), ['redirect_uri' => 'https://some.url']);
        $this->assertSame($expectedToken, $actualToken);
    }

    public function testGetAccessTokenThrowsInvalidStateException()
    {
        $this->expectException(InvalidStateException::class);
        $this->request->query->set('state', 'ACTUAL_STATE');
        $this->session->set(OAuth2Client::OAUTH2_SESSION_STATE_KEY, 'OTHER_STATE');

        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );
        $client->getAccessToken();
    }

    public function testGetAccessTokenThrowsMissingAuthCodeException()
    {
        $this->expectException(MissingAuthorizationCodeException::class);
        $this->request->query->set('state', 'ACTUAL_STATE');
        $this->session->set(OAuth2Client::OAUTH2_SESSION_STATE_KEY, 'ACTUAL_STATE');

        // don't set a code query parameter
        $client = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );
        $client->getAccessToken();
    }

    public function testFetchUser()
    {
        $this->request->request->set('code', 'CODE_ABC');

        $expectedToken = new AccessToken(['access_token' => 'expected']);
        $this->provider->method('getAccessToken')
            ->with('authorization_code', ['code' => 'CODE_ABC'])
            ->willReturn($expectedToken);

        $client = new OAuth2Client(
            $this->provider,
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

        $this->provider->method('getResourceOwner')
            ->with($actualToken)
            ->willReturn($resourceOwner);
        $user = $client->fetchUser();

        $this->assertInstanceOf(FacebookUser::class, $user);
        $this->assertEquals('testUser', $user->getName());
    }

    public function testShouldReturnProviderObject()
    {
        $testClient = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );

        $result = $testClient->getOAuth2Provider();

        $this->assertInstanceOf(AbstractProvider::class, $result);
    }

    public function testShouldThrowExceptionOnRedirectIfNoSessionAndNotRunningStateless()
    {
        $this->requestStack = new RequestStack();
        $this->requestStack->push(new Request());

        $testClient = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );

        $this->expectException(\LogicException::class);
        $testClient->redirect();
    }

    public function testShouldThrowExceptionOnGetAccessTokenIfNoSessionAndNotRunningStateless()
    {
        $this->requestStack = new RequestStack();
        $this->requestStack->push(new Request());

        $testClient = new OAuth2Client(
            $this->provider,
            $this->requestStack
        );

        $this->expectException(\LogicException::class);
        $testClient->getAccessToken();
    }

    public function testShouldThrowExceptionIfThereIsNoRequestInTheStack()
    {
        $testClient = new OAuth2Client(
            $this->provider,
            new RequestStack()
        );

        $this->expectException(\LogicException::class);
        $testClient->getAccessToken();
    }
}
