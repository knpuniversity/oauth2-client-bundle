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
use League\OAuth2\Client\Token\AccessTokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Subclass of OAuth2Client for handling OAuth 2.0 providers using PKCE extension (https://oauth.net/2/pkce/).
 *
 * @author Muhammad Lukman Nasaruddin (https://github.com/MLukman/)
 */
class OAuth2PKCEClient extends OAuth2Client
{
    public const VERIFIER_KEY = 'pkce_code_verifier';

    /**
     * Enhance the RedirectResponse prepared by OAuth2Client::redirect() with
     * PKCE code challenge and code challenge method parameters.
     *
     * @see OAuth2Client::redirect()
     *
     * @return RedirectResponse
     */
    public function redirect(array $scopes = [], array $options = [])
    {
        $this->getSession()->set(static::VERIFIER_KEY, $code_verifier = bin2hex(random_bytes(64)));
        $pkce = [
            'code_challenge' => rtrim(strtr(base64_encode(hash('sha256', $code_verifier, true)), '+/', '-_'), '='),
            'code_challenge_method' => 'S256',
        ];

        return parent::redirect($scopes, $options + $pkce);
    }

    /**
     * Enhance the token exchange calls by OAuth2Client::getAccessToken() with
     * PKCE code verifier parameter.
     *
     * @see OAuth2Client::getAccessToken()
     *
     * @return AccessToken|AccessTokenInterface
     *
     * @throws \LogicException When there is no code verifier found in the session
     */
    public function getAccessToken(array $options = [])
    {
        $session = $this->getSession();

        if (!$session->has(static::VERIFIER_KEY)) {
            throw new \LogicException('Unable to fetch token from OAuth2 server because there is no PKCE code verifier stored in the session');
        }

        $pkce = ['code_verifier' => $session->get(static::VERIFIER_KEY)];
        $session->remove(static::VERIFIER_KEY);

        return parent::getAccessToken($options + $pkce);
    }

    /**
     * @return SessionInterface
     */
    protected function getSession(bool $isPKCE = true)
    {
        return parent::getSession($isPKCE);
    }
}
