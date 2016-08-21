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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Use this trait if sometimes your authenticator requires people
 * to "finish registration" before logging in.
 */
trait FinishRegistrationBehavior
{
    /**
     * Call this from within your onAuthenticationFailure() method.
     *
     * @param Request $request
     * @param FinishRegistrationException $e
     * @return RedirectResponse
     */
    protected function saveUserInfoToSession(Request $request, FinishRegistrationException $e)
    {
        // save the user information!
        $request->getSession()->set(
            'guard.finish_registration.user_information',
            $e->getUserInformation()
        );
    }

    /**
     * Useful during registration to get your user information back out.
     *
     * @param Request $request
     * @return mixed
     */
    public function getUserInfoFromSession(Request $request)
    {
        return $request->getSession()->get('guard.finish_registration.user_information');
    }
}
