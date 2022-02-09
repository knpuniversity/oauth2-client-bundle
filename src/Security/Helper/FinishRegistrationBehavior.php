<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Security\Helper;

use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Use this trait if sometimes your authenticator requires people
 * to "finish registration" before logging in.
 */
trait FinishRegistrationBehavior
{
    /**
     * Call this from within your onAuthenticationFailure() method.
     *
     * @throws LogicException
     */
    protected function saveUserInfoToSession(Request $request, FinishRegistrationException $e)
    {
        // save the user information!
        if (!$request->hasSession() || !$request->getSession() instanceof SessionInterface) {
            throw new LogicException('In order to save user info, you must have a session available.');
        }
        $session = $request->getSession();

        $session->set(
            'guard.finish_registration.user_information',
            $e->getUserInformation()
        );
    }

    /**
     * Useful during registration to get your user information back out.
     *
     * @return mixed
     *
     * @throws LogicException
     */
    public function getUserInfoFromSession(Request $request)
    {
        if (!$request->hasSession() || !$request->getSession() instanceof SessionInterface) {
            throw new LogicException('In order to have saved user info, you must have a session available.');
        }
        $session = $request->getSession();

        return $session->get('guard.finish_registration.user_information');
    }
}
