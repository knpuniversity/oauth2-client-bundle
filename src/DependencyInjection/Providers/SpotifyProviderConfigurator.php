<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Kerox\OAuth2\Client\Provider\Spotify;
use KnpU\OAuth2ClientBundle\Client\Provider\SpotifyClient;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class SpotifyProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node): void
    {
        // no custom options
    }

    public function getProviderClass(array $configuration): string
    {
        return Spotify::class;
    }

    public function getClientClass(array $config): string
    {
        return SpotifyClient::class;
    }

    public function getProviderOptions(array $configuration): array
    {
        return [
            'clientId' => $configuration['client_id'],
            'clientSecret' => $configuration['client_secret'],
        ];
    }

    public function getPackagistName(): string
    {
        return 'kerox/oauth2-spotify';
    }

    public function getLibraryHomepage(): string
    {
        return 'https://github.com/ker0x/oauth2-spotify';
    }

    public function getProviderDisplayName(): string
    {
        return 'Spotify';
    }
}
