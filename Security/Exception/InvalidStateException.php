<?php

namespace KnpU\OAuth2ClientBundle\Exception;

use Exception;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidStateException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'Invalid OAuth state! Please try again.';
    }
}
