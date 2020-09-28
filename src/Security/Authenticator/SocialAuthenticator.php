<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Security\Authenticator;

use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use KnpU\OAuth2ClientBundle\Exception\MissingAuthorizationCodeException;
use KnpU\OAuth2ClientBundle\Security\Exception\IdentityProviderAuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Exception\InvalidStateAuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Helper\FinishRegistrationBehavior;
use KnpU\OAuth2ClientBundle\Security\Helper\PreviousUrlHelper;
use KnpU\OAuth2ClientBundle\Security\Helper\SaveAuthFailureMessage;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

abstract class SocialAuthenticator extends AbstractGuardAuthenticator
{
    use FinishRegistrationBehavior;
    use PreviousUrlHelper;
    use SaveAuthFailureMessage;

    protected function fetchAccessToken(OAuth2ClientInterface $client, array $options = [])
    {
        try {
            return $client->getAccessToken($options);
        } catch (MissingAuthorizationCodeException $e) {
            throw new NoAuthCodeAuthenticationException();
        } catch (IdentityProviderException $e) {
            throw new IdentityProviderAuthenticationException($e);
        } catch (InvalidStateException $e) {
            throw new InvalidStateAuthenticationException($e);
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // do nothing - the fact that the access token works is enough
        return true;
    }

    public function supportsRememberMe(): bool
    {
        return true;
    }
}
