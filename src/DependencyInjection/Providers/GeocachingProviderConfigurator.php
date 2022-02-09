<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use KnpU\OAuth2ClientBundle\Client\Provider\GeocachingClient;
use League\OAuth2\Client\Provider\Geocaching;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class GeocachingProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
        ->scalarNode('environment')
            ->isRequired()
            ->info('dev, staging or production')
            ->example('environment: production')
        ->end()
    ;
    }

    public function getProviderClass(array $config)
    {
        return Geocaching::class;
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'environment' => $config['environment'],
        ];
    }

    public function getPackagistName()
    {
        return 'surfoo/oauth2-geocaching';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/surfoo/oauth2-geocaching';
    }

    public function getProviderDisplayName()
    {
        return 'Geocaching';
    }

    public function getClientClass(array $config)
    {
        return GeocachingClient::class;
    }
}
