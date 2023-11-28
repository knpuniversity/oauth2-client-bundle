<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUser implements UserInterface
{
    private $username;
    private array $roles;

    public function __construct($username, array $roles)
    {
        $this->username = $username;
        $this->roles = $roles;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return '';
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @deprecated use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        // Do nothing.
    }
}
