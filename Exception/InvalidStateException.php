<?php

namespace KnpU\OAuth2ClientBundle\Extension;

/**
 * Thrown if the "state" is invalid after the auth code redirect
 */
class InvalidStateException extends \RuntimeException implements OAuth2ClientException
{

}
