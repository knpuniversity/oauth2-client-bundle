<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Security\Exception;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Thrown if an IdentityProviderException occurs during authentication.
 */
class IdentityProviderAuthenticationException extends AuthenticationException
{
    public function __construct(IdentityProviderException $e)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e);
    }

    public function getMessageKey(): string
    {
        return 'Error fetching OAuth credentials: "%error%".';
    }

    public function getMessageData()
    {
        return [
            '%error%' => $this->getMessage(),
        ];
    }
}
