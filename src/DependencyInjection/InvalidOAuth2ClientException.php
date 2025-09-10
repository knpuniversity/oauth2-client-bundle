<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use KnpU\OAuth2ClientBundle\Exception\OAuth2ClientException;

class InvalidOAuth2ClientException extends \InvalidArgumentException implements OAuth2ClientException
{
}
