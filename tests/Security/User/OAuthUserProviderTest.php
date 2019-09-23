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
use KnpU\OAuth2ClientBundle\Security\User\OAuthUserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use PHPUnit\Framework\TestCase;

class OAuthUserProviderTest extends TestCase
{
    public function testLoadUserByUsername()
    {
        $userProvider = new OAuthUserProvider(['role 1', 'role 2']);

        $expected = new OAuthUser('username', ['role 1', 'role 2']);

        $this->assertEquals($expected, $userProvider->loadUserByUsername('username'));
    }

    public function testRefreshUser()
    {
        $userProvider = new OAuthUserProvider(['role 1', 'role 2']);

        $user = new OAuthUser('username', ['role 3']);
        $expected = new OAuthUser('username', ['role 1', 'role 2']);

        $this->assertEquals($expected, $userProvider->refreshUser($user));
    }

    public function testRefreshOtherUser()
    {
        $this->expectException(UnsupportedUserException::class);
        $userProvider = new OAuthUserProvider();

        $userProvider->refreshUser($this->createMock(UserInterface::class));
    }

    /**
     * @dataProvider supportsClassProvider
     */
    public function testSupportsClass($class, $supports)
    {
        $userProvider = new OAuthUserProvider();

        $this->assertSame($supports, $userProvider->supportsClass($class));
    }

    public function supportsClassProvider()
    {
        yield 'OAuthUser' => [OAuthUser::class, true];
        yield 'other user' => [User::class, false];
    }
}
