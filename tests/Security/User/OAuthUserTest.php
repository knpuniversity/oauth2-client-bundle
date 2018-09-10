<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\User;

use KnpU\OAuth2ClientBundle\Security\User\OAuthUser;
use PHPUnit\Framework\TestCase;

class OAuthUserTest extends TestCase
{
    public function testRoles()
    {
        $user = new OAuthUser('username', ['role 1', 'role 2']);

        $this->assertSame(['role 1', 'role 2'], $user->getRoles());
    }

    public function testPassword()
    {
        $user = new OAuthUser('username', ['role 1', 'role 2']);

        $this->assertSame('', $user->getPassword());
    }

    public function testSalt()
    {
        $user = new OAuthUser('username', ['role 1', 'role 2']);

        $this->assertNull($user->getSalt());
    }

    public function testUsername()
    {
        $user = new OAuthUser('username', ['role 1', 'role 2']);

        $this->assertSame('username', $user->getUsername());
    }
}
