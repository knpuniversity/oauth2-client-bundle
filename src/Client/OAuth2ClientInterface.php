<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Client;

use League\OAuth2\Client\Token\AccessToken;

/**
 * @method AccessToken refreshAccessToken(string $refreshToken, array $options = []) Get a new AccessToken from a refresh token., passing options to the underlying provider
 */
interface OAuth2ClientInterface
{
    /**
     * Call this to avoid using and checking "state".
     */
    public function setAsStateless();

    /**
     * Creates a RedirectResponse that will send the user to the
     * OAuth2 server (e.g. send them to Facebook).
     *
     * @param array $scopes  The scopes you want (leave empty to use default)
     * @param array $options Extra options to pass to the Provider's getAuthorizationUrl()
     *                       method. For example, <code>scope</code> is a common option.
     *                       Generally, these become query parameters when redirecting.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(array $scopes, array $options);

    /**
     * Call this after the user is redirected back to get the access token.
     *
     * @param array $options Additional options that should be passed to the getAccessToken() of the underlying provider
     *
     * @return \League\OAuth2\Client\Token\AccessToken
     *
     * @throws \KnpU\OAuth2ClientBundle\Exception\InvalidStateException
     * @throws \KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException   If token cannot be fetched
     */
    public function getAccessToken(array $options = []);

    /**
     * Returns the "User" information (called a resource owner).
     *
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUserFromToken(AccessToken $accessToken);

    /**
     * Shortcut to fetch the access token and user all at once.
     *
     * Only use this if you don't need the access token, but only
     * need the user.
     *
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUser();

    /**
     * Returns the underlying OAuth2 provider.
     *
     * @return \League\OAuth2\Client\Provider\AbstractProvider
     */
    public function getOAuth2Provider();
}
