<?php

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Token\AccessToken;

/**
 * GitlabClient.
 *
 * @author Niels Keurentjes <niels.keurentjes@omines.com>
 */
class GitlabClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return GitlabResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return GitlabResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}