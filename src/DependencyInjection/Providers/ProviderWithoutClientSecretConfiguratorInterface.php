<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

interface ProviderWithoutClientSecretConfiguratorInterface
{
    /**
     * Implement and return false for any providers that do not
     * require a "client_secret" - like apple.
     */
    public function needsClientSecret(): bool;
}
