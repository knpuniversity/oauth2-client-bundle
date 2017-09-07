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

class MicrosoftProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('url_authorize')
                ->defaultNull()
                ->info('Optional value for URL Authorize')
            ->end()
            ->scalarNode('url_access_token')
                ->defaultNull()
                ->info('Optional value for URL Access Token')
            ->end()
            ->scalarNode('url_resource_owner_details')
                ->defaultNull()
                ->info('Optional value for URL Resource Owner Details')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'Stevenmaguire\OAuth2\Client\Provider\Microsoft';
    }

    public function getProviderOptions(array $config)
    {
        $options = [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];

        if ($config['url_authorize']) {
            $options['urlAuthorize'] = $config['url_authorize'];
        }

        if ($config['url_access_token']) {
            $options['urlAccessToken'] = $config['url_access_token'];
        }

        if ($config['url_resource_owner_details']) {
            $options['urlResourceOwnerDetails'] = $config['url_resource_owner_details'];
        }

        return $options;
    }

    public function getPackagistName()
    {
        return 'stevenmaguire/oauth2-microsoft';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/stevenmaguire/oauth2-microsoft';
    }

    public function getProviderDisplayName()
    {
        return 'Microsoft';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\MicrosoftClient';
    }
}
