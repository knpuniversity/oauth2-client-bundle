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
use League\OAuth2\Client\Token\AccessToken;
use Saf33r\OAuth2\Client\Provider\VimeoResourceOwner;

class VimeoClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return VimeoResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return VimeoResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
