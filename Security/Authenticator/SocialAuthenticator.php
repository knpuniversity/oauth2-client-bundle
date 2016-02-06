<?php

namespace KnpU\OAuth2ClientBundle\Security\Authenticator;

use KnpU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;
use KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException;
use KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use KnpU\OAuth2ClientBundle\Security\Helper\PreviousUrlHelper;
use KnpU\OAuth2ClientBundle\Security\Helper\SaveAuthFailureMessage;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;

abstract class SocialAuthenticator extends AbstractGuardAuthenticator
{
    use FinishRegistrationBehavior;
    use PreviousUrlHelper;
    use SaveAuthFailureMessage;

    protected function fetchAccessToken(OAuth2Client $client)
    {
        try {
            return $client->getAccessToken();
        } catch (MissingAuthorizationCodeException $e) {
            throw new NoAuthCodeAuthenticationException();
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // do nothing - the fact that the access token works is enough
        return true;
    }

    public function supportsRememberMe()
    {
        return true;
    }
}
