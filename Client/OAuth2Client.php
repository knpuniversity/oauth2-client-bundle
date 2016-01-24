<?php

namespace KnpU\OAuth2ClientBundle\Client;

use League\OAuth2\Client\Provider\AbstractProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class OAuth2Client
{
    private $provider;

    private $requestStack;

    private $stateless = false;

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
        $this->stateless = true;
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
        $url = $this->provider->getAuthorizationUrl([
            'scopes' => $scopes,
        ]);

        // set the state (unless we're stateless)
        if (!$this->stateless) {
            $this->getSession()->set(
                self::OAUTH2_SESSION_STATE_KEY,
                $this->provider->getState()
            );
        }

        return new RedirectResponse($url);
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
            throw new \LogicException('There is not "current request", and it is needed to perform this action');
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
