<?php

namespace KnpU\OAuth2ClientBundle\Client;

use KnpU\OAuth2ClientBundle\Extension\InvalidStateException;
use KnpU\OAuth2ClientBundle\Extension\MissingAuthorizationCodeException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class OAuth2Client
{
    private $provider;

    private $requestStack;

    private $isStateless = false;

    const OAUTH2_SESSION_STATE_KEY = 'knpu.oauth2_client_state';

    public function __construct(AbstractProvider $provider, RequestStack $requestStack)
    {
        $this->provider = $provider;
        $this->requestStack = $requestStack;
    }

    /**
     * Call this to avoid using and checking "state"
     */
    public function setAsStateless()
    {
        $this->isStateless = true;
    }

    /**
     * Creates a RedirectResponse that will send the user to the
     * OAuth2 server (e.g. send them to Facebook)
     *
     * @param array $scopes The scopes you want (leave empty to use default)
     * @return RedirectResponse
     */
    public function redirect(array $scopes = array())
    {
        $options = array();
        if (!empty($scopes)) {
            $options['scopes'] = $scopes;
        }

        $url = $this->provider->getAuthorizationUrl($options);

        // set the state (unless we're stateless)
        if (!$this->isStateless) {
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
     * @return \League\OAuth2\Client\Token\AccessToken
     *
     * @throws InvalidStateException
     * @throws MissingAuthorizationCodeException
     * @throws IdentityProviderException If token cannot be fetched
     */
    public function getAccessToken()
    {
        if (!$this->isStateless) {
            $expectedState = $this->getSession()->get(self::OAUTH2_SESSION_STATE_KEY);
            $actualState = $this->getCurrentRequest()->query->get('state');
            if (!$actualState || ($actualState !== $expectedState)) {
                throw new InvalidStateException('Invalid state');
            }
        }

        $code = $this->getCurrentRequest()->query->get('code');

        if (!$code) {
            throw new MissingAuthorizationCodeException('No "code" query parameter was found!');
        }

        return $this->provider->getAccessToken('authorization_code', array(
            'code' => $code
        ));
    }

    /**
     * Returns the "User" information (called a resource owner).
     *
     * @param AccessToken $accessToken
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
        $token = $this->getAccessToken();

        return $this->fetchUserFromToken($token);
    }

    /**
     * Returns the underlying OAuth2 provider
     *
     * @return AbstractProvider
     */
    public function getOAuth2Provider()
    {
        return $this->provider;
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
     * @return null|\Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private function getSession()
    {
        $session = $this->getCurrentRequest()->getSession();

        if (!$session) {
            throw new \LogicException('In order to use "state", you must have a session. Set the OAuth2Client to stateless to avoid state');
        }

        return $session;
    }
}
