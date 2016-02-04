<?php

namespace KnpU\OAuth2ClientBundle\Authenticator;

use KnpU\OAuth2ClientBundle\Exception\NoAuthCodeAuthenticationException;
use KnpU\OAuth2ClientBundle\Extension\MissingAuthorizationCodeException;
use KnpU\OAuth2ClientBundle\Helper\FinishRegistrationBehavior;
use KnpU\OAuth2ClientBundle\Helper\PreviousUrlHelper;
use KnpU\OAuth2ClientBundle\Helper\SaveAuthFailureMessage;
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
