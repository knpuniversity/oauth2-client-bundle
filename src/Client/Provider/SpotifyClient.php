<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use Kerox\OAuth2\Client\Provider\SpotifyResourceOwner;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;

class SpotifyClient extends OAuth2Client
{
    /**
     * @return SpotifyResourceOwner|ResourceOwnerInterface
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return SpotifyResourceOwner|ResourceOwnerInterface
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
