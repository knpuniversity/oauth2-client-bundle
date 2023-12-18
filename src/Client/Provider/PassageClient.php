<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\Passage;
use League\OAuth2\Client\Provider\PassageUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PassageClient extends OAuth2Client
{
    /**
     * @return PassageUser|ResourceOwnerInterface
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return PassageUser|ResourceOwnerInterface
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }

    public function logout(): RedirectResponse
    {
        $provider = $this->getOAuth2Provider();

        if (!($provider instanceof Passage)) {
            throw new \RuntimeException('Invalid provider "'.$provider::class.'", expected provider "'.Passage::class.'"');
        }

        return new RedirectResponse($provider->getLogoutUrl());
    }
}
