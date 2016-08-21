<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Exception;

/**
 * Thrown when there *should* be a code query param, but there is not.
 */
class MissingAuthorizationCodeException extends \RuntimeException implements OAuth2ClientException
{
}
