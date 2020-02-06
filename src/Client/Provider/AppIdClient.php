<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use Jampire\OAuth2\Client\Provider\AppIdResourceOwner;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Class AppIdClient.
 *
 * @author  Dzianis Kotau <jampire.blr@gmail.com>
 */
class AppIdClient extends OAuth2Client
{
    /**
     * @return AppIdResourceOwner|ResourceOwnerInterface
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return AppIdResourceOwner|ResourceOwnerInterface
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
