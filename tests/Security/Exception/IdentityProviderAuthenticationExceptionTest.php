<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Exception;

use KnpU\OAuth2ClientBundle\Security\Exception\IdentityProviderAuthenticationException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use PHPUnit\Framework\TestCase;

class IdentityProviderAuthenticationExceptionTest extends TestCase
{
    public function testException()
    {
        $mockProviderException = new IdentityProviderException("Message", 0, "Response");

        $testException = new IdentityProviderAuthenticationException($mockProviderException);
        $this->assertEquals('Error fetching OAuth credentials: "%error%".', $testException->getMessageKey());
        $this->assertEquals(["%error%" => "Message"], $testException->getMessageData());
    }
}
