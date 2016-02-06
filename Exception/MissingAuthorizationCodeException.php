<?php

namespace KnpU\OAuth2ClientBundle\Exception;

/**
 * Thrown when there *should* be a code query param, but there is not.
 */
class MissingAuthorizationCodeException extends \RuntimeException implements OAuth2ClientException
{
}
