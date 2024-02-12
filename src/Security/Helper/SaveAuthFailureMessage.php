<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Security\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

trait SaveAuthFailureMessage
{
    protected function saveAuthenticationErrorToSession(Request $request, AuthenticationException $exception)
    {
        if (!$request->hasSession() || !$request->getSession() instanceof SessionInterface) {
            throw new \LogicException('In order to save an authentication error, you must have a session available.');
        }
        
        $securityClass = class_exists(SecurityRequestAttributes::class)
            ? SecurityRequestAttributes::class
            : Security::class;

        $request->getSession()->set($securityClass::AUTHENTICATION_ERROR, $exception);
    }
}
