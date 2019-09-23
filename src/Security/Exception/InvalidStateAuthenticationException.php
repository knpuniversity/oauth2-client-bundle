<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Security\Exception;

use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Thrown when an InvalidStateException is thrown during authentication.
 */
class InvalidStateAuthenticationException extends AuthenticationException
{
    public function __construct(InvalidStateException $e)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e);
    }

    public function getMessageKey(): string
    {
        return 'Invalid state parameter passed in callback URL.';
    }
}
