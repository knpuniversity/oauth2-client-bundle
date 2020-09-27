<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Exception;

use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use KnpU\OAuth2ClientBundle\Security\Exception\InvalidStateAuthenticationException;
use PHPUnit\Framework\TestCase;

class InvalidStateAuthenticationExceptionTest extends TestCase
{
    public function testException()
    {
        $testException = new InvalidStateAuthenticationException(new InvalidStateException());

        $this->assertEquals('Invalid state parameter passed in callback URL.', $testException->getMessageKey());
    }
}
