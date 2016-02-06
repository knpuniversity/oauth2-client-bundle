<?php

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\LinkedInResourceOwner;

class LinkedInClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return LinkedInResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return LinkedInResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
