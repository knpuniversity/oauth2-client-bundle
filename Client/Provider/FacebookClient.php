<?php

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;

class FacebookClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return FacebookUser
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return FacebookUser
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
