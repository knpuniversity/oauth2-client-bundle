<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Authenticator;

use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Security\Exception\IdentityProviderAuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Exception\InvalidStateAuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use PHPUnit\Framework\TestCase;

class SocialAuthenticatorTest extends TestCase
{
    public function testFetchAccessTokenSimplyReturns()
    {
        $authenticator = new StubSocialAuthenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willReturn('expected_access_token');

        $actualToken = $authenticator->doFetchAccessToken($client->reveal());
        $this->assertEquals('expected_access_token', $actualToken);
    }

    public function testFetchAccessTokenThrowsAuthenticationException()
    {
        $this->expectException(NoAuthCodeAuthenticationException::class);
        $authenticator = new StubSocialAuthenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willThrow(new MissingAuthorizationCodeException());

        $authenticator->doFetchAccessToken($client->reveal());
    }

    public function testFetchAccessTokenThrowsIdentityProviderException()
    {
        $this->expectException(IdentityProviderAuthenticationException::class);
        $authenticator = new StubSocialAuthenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willThrow(new IdentityProviderException("message", 42, "response"));

        $authenticator->doFetchAccessToken($client->reveal());
    }

    public function testFetchAccessTokenThrowsInvalidStateException()
    {
        $this->expectException(InvalidStateAuthenticationException::class);
        $authenticator = new StubSocialAuthenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willThrow(new InvalidStateException());

        $authenticator->doFetchAccessToken($client->reveal());
    }

    public function testCheckCredentials()
    {
        $authenticator = new StubSocialAuthenticator();
        $user = new SomeUser();
        $this->assertEquals(true, $authenticator->checkCredentials('', $user));
    }

    public function testSupportsRememberMe()
    {
        $authenticator = new StubSocialAuthenticator();
        $this->assertEquals(true, $authenticator->supportsRememberMe());
    }
}

class StubSocialAuthenticator extends SocialAuthenticator
{
    public function doFetchAccessToken(OAuth2Client $client)
    {
        return $this->fetchAccessToken($client);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
    }
    public function supports(Request $request): bool
    {
    }
    public function getCredentials(Request $request)
    {
    }
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
    }
}

class SomeUser implements UserInterface
{
    public function getRoles()
    {
    }

    public function getPassword(): ?string
    {
    }

    public function getSalt(): ?string
    {
    }

    public function getUsername(): string
    {
    }

    public function eraseCredentials()
    {
    }
}
