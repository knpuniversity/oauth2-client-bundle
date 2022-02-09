<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class FacebookProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('graph_api_version')
                ->isRequired()
                ->example('graph_api_version: v2.12')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'League\OAuth2\Client\Provider\Facebook';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'graphApiVersion' => $config['graph_api_version'],
        ];
    }

    public function getPackagistName()
    {
        return 'league/oauth2-facebook';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/thephpleague/oauth2-facebook';
    }

    public function getProviderDisplayName()
    {
        return 'Facebook';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient';
    }
}
