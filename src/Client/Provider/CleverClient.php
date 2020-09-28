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
use Schoolrunner\OAuth2\Client\User\CleverUser;

class CleverClient extends OAuth2Client
{
    /**
     * @return CleverUser|\League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return CleverUser|\League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
