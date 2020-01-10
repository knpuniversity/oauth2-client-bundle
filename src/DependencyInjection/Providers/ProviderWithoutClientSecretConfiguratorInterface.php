<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

interface ProviderWithoutClientSecretConfiguratorInterface
{
    /**
     * Implement and return false for any providers that do not
     * require a "client_secret" - like apple.
     */
    public function needsClientSecret(): bool;
}
