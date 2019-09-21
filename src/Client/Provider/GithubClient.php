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
use League\OAuth2\Client\Provider\GithubResourceOwner;

class GithubClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return GithubResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        $resourceOwner = parent::fetchUserFromToken($accessToken);

        if (null === $resourceOwner->getEmail()) {
            $response = $this->getOAuth2Provider()->getHttpClient()->request(
                'GET',
                'https://api.github.com/user/emails',
                [
                    'headers' => [
                        'Authorization' => "Bearer {$accessToken->getToken()}"
                    ]
                ]
            );

            foreach (json_decode($response->getBody()->getContents(), true) as $email) {
                if (true === $email['primary']) {
                    return new GithubResourceOwner(\array_merge($resourceOwner->toArray(), [
                        'email' => $email['email']
                    ]));
                }
            }
        }

        return $resourceOwner;
    }

    /**
     * @return GithubResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
