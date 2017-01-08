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
use J4k\OAuth2\Client\Provider\Vkontakte;

class VkontakteClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return Vkontakte
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return Vkontakte
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
