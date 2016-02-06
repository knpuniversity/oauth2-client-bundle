<?php

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\GithubResourceOwner;

class GithubClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return GithubResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return GithubResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
