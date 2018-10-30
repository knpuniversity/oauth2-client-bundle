<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Client;

use League\OAuth2\Client\Token\AccessToken;

interface OAuth2ClientInterface
{
    public function setAsStateless();

    public function redirect(array $scopes, array $options);

    public function getAccessToken();

    public function fetchUserFromToken(AccessToken $accessToken);

    public function fetchUser();

    public function getOAuth2Provider();
}
