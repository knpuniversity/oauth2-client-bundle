<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use KnpU\OAuth2ClientBundle\Client\Provider\InstagramClient;
use League\OAuth2\Client\Provider\Instagram;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class InstagramProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // no options...
    }

    public function getProviderClass(array $config)
    {
        return Instagram::class;
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];
    }

    public function getPackagistName()
    {
        return 'league/oauth2-instagram';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/thephpleague/oauth2-instagram';
    }

    public function getProviderDisplayName()
    {
        return 'Instagram';
    }

    public function getClientClass(array $config)
    {
        return InstagramClient::class;
    }
}
