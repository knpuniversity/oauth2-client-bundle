<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Client;

use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OAuth2Client implements OAuth2ClientInterface
{
    const OAUTH2_SESSION_STATE_KEY = 'knpu.oauth2_client_state';

    /** @var AbstractProvider */
    private $provider;

    /** @var RequestStack */
    private $requestStack;

    /** @var bool */
    private $isStateless = false;

    /**
     * OAuth2Client constructor.
     */
    public function __construct(AbstractProvider $provider, RequestStack $requestStack)
    {
        $this->provider = $provider;
        $this->requestStack = $requestStack;
    }

    /**
     * Call this to avoid using and checking "state".
     */
    public function setAsStateless()
    {
        $this->isStateless = true;
    }

    /**
     * Creates a RedirectResponse that will send the user to the
     * OAuth2 server (e.g. send them to Facebook).
     *
     * @param array $scopes  The scopes you want (leave empty to use default)
     * @param array $options Extra options to pass to the Provider's getAuthorizationUrl()
     *                       method. For example, <code>scope</code> is a common option.
     *                       Generally, these become query parameters when redirecting.
     *
     * @return RedirectResponse
     */
    public function redirect(array $scopes = [], array $options = [])
    {
        if (!empty($scopes)) {
            $options['scope'] = $scopes;
        }

        $url = $this->provider->getAuthorizationUrl($options);

        // set the state (unless we're stateless)
        if (!$this->isStateless()) {
            $this->getSession()->set(
                self::OAUTH2_SESSION_STATE_KEY,
                $this->provider->getState()
            );
        }

        return new RedirectResponse($url);
    }

    /**
     * Call this after the user is redirected back to get the access token.
     *
     * @param array $options Additional options that should be passed to the getAccessToken() of the underlying provider
     *
     * @return AccessToken|\League\OAuth2\Client\Token\AccessTokenInterface
     *
     * @throws InvalidStateException
     * @throws MissingAuthorizationCodeException
     * @throws IdentityProviderException         If token cannot be fetched
     */
    public function getAccessToken(array $options = [])
    {
        if (!$this->isStateless()) {
            $expectedState = $this->getSession()->get(self::OAUTH2_SESSION_STATE_KEY);
            $actualState = $this->getCurrentRequest()->get('state');
            if (!$actualState || ($actualState !== $expectedState)) {
                throw new InvalidStateException('Invalid state');
            }
        }

        $code = $this->getCurrentRequest()->get('code');

        if (!$code) {
            throw new MissingAuthorizationCodeException('No "code" parameter was found (usually this is a query parameter)!');
        }

        return $this->provider->getAccessToken(
            'authorization_code',
            array_merge(['code' => $code], $options)
        );
    }

    /**
     * Get a new AccessToken from a refresh token.
     *
     * @param array $options Additional options that should be passed to the getAccessToken() of the underlying provider
     *
     * @return AccessToken|\League\OAuth2\Client\Token\AccessTokenInterface
     *
     * @throws IdentityProviderException If token cannot be fetched
     */
    public function refreshAccessToken(string $refreshToken, array $options = [])
    {
        return $this->provider->getAccessToken(
            'refresh_token',
            array_merge(['refresh_token' => $refreshToken], $options)
        );
    }

    /**
     * Returns the "User" information (called a resource owner).
     *
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return $this->provider->getResourceOwner($accessToken);
    }

    /**
     * Shortcut to fetch the access token and user all at once.
     *
     * Only use this if you don't need the access token, but only
     * need the user.
     *
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUser()
    {
        /** @var AccessToken $token */
        $token = $this->getAccessToken();

        return $this->fetchUserFromToken($token);
    }

    /**
     * Returns the underlying OAuth2 provider.
     *
     * @return AbstractProvider
     */
    public function getOAuth2Provider()
    {
        return $this->provider;
    }

    protected function isStateless(): bool
    {
        return $this->isStateless;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getCurrentRequest()
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \LogicException('There is no "current request", and it is needed to perform this action');
        }

        return $request;
    }

    /**
     * @return SessionInterface
     */
    private function getSession()
    {
        if (!$this->getCurrentRequest()->hasSession()) {
            throw new \LogicException('In order to use "state", you must have a session. Set the OAuth2Client to stateless to avoid state');
        }

        return $this->getCurrentRequest()->getSession();
    }
}
