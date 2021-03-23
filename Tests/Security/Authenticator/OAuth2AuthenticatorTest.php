<?php


namespace KnpU\OAuth2ClientBundle\Tests\Security\Authenticator;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use KnpU\OAuth2ClientBundle\Security\Exception\IdentityProviderAuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Exception\InvalidStateAuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class OAuth2AuthenticatorTest extends TestCase
{
    public function setUp(): void
    {
        if (!class_exists(AbstractAuthenticator::class)) {
            $this->markTestSkipped('Unable to use AbstractAuthenticator');
        }
    }

    public function testFetchAccessTokenSimplyReturns()
    {
        $authenticator = new StubOauth2Authenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willReturn('expected_access_token');

        $actualToken = $authenticator->doFetchAccessToken($client->reveal());
        $this->assertEquals('expected_access_token', $actualToken);
    }

    public function testFetchAccessTokenThrowsAuthenticationException()
    {
        $this->expectException(NoAuthCodeAuthenticationException::class);
        $authenticator = new StubOauth2Authenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willThrow(new MissingAuthorizationCodeException());

        $authenticator->doFetchAccessToken($client->reveal());
    }

    public function testFetchAccessTokenThrowsIdentityProviderException()
    {
        $this->expectException(IdentityProviderAuthenticationException::class);
        $authenticator = new StubOauth2Authenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willThrow(new IdentityProviderException("message", 42, "response"));

        $authenticator->doFetchAccessToken($client->reveal());
    }

    public function testFetchAccessTokenThrowsInvalidStateException()
    {
        $this->expectException(InvalidStateAuthenticationException::class);
        $authenticator = new StubOauth2Authenticator();
        $client = $this->prophesize('KnpU\OAuth2ClientBundle\Client\OAuth2Client');
        $client->getAccessToken([])
            ->willThrow(new InvalidStateException());

        $authenticator->doFetchAccessToken($client->reveal());
    }
}

if (class_exists(AbstractAuthenticator::class)) {
    class StubOauth2Authenticator extends OAuth2Authenticator
    {
        public function doFetchAccessToken(OAuth2Client $client)
        {
            return $this->fetchAccessToken($client);
        }

        public function supports(Request $request): ?bool
        {
        }

        public function authenticate(Request $request): PassportInterface
        {
        }

        public function onAuthenticationSuccess(
            Request $request,
            TokenInterface $token,
            string $firewallName
        ): ?Response {
        }

        public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
        {
        }
    }
}
