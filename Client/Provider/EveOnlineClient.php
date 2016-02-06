<?php

namespace KnpU\OAuth2ClientBundle\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use Evelabs\OAuth2\Client\Provider\EveOnlineResourceOwner;
use League\OAuth2\Client\Token\AccessToken;

class EveOnlineClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return EveOnlineResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return EveOnlineResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
