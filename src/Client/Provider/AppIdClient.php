<?php

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Jampire\OAuth2\Client\Provider\AppIdResourceOwner;

/**
 * Class AppIdClient
 *
 * @author  Dzianis Kotau <jampire.blr@gmail.com>
 * @package KnpU\OAuth2ClientBundle\Client\Provider
 */
class AppIdClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     *
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
