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

class AzureProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('url_login')
                ->info('Domain to build login URL')
                ->example("url_login: 'https://login.microsoftonline.com/'")
                ->defaultValue('https://login.microsoftonline.com/')
            ->end()
            ->scalarNode('path_authorize')
                ->example("path_authorize: '/oauth2/authorize'")
                ->info('Oauth path to authorize against')
                ->defaultValue('/oauth2/authorize')
            ->end()
            ->scalarNode('path_token')
                ->info('Oauth path to retrieve a token')
                ->example("path_token: '/oauth2/token'")
                ->defaultValue('/oauth2/token')
            ->end()
            ->arrayNode('scope')
                ->info('Oauth scope send with the request')
                ->prototype('scalar')->end()
            ->end()
            ->scalarNode('tenant')
                ->example("tenant: 'common'")
                ->info('The tenant to use, default is `common`')
                ->defaultValue('common')
            ->end()
            ->scalarNode('url_api')
                ->example("url_api: 'https://graph.windows.net/'")
                ->info('Domain to build request URL')
                ->defaultValue('https://graph.windows.net/')
            ->end()
            ->scalarNode('resource')
                ->info('Oauth resource field')
                ->defaultNull()
            ->end()
            ->scalarNode('api_version')
                ->example("api_version: '1.6'")
                ->info('The API version to run against')
                ->defaultValue('1.6')
            ->end()
            ->booleanNode('auth_with_resource')
                ->example('auth_with_resource: true')
                ->info('Send resource field with auth-request')
                ->defaultTrue()
            ->end()
            ->scalarNode('default_end_point_version')
                ->example("default_end_point_version: '1.0'")
                ->info('The endpoint version to run against')
                ->defaultValue('1.0')
            ->end();
    }

    public function getProviderClass(array $config)
    {
        return 'TheNetworg\OAuth2\Client\Provider\Azure';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'urlLogin' => $config['url_login'],
            'pathAuthorize' => $config['path_authorize'],
            'pathToken' => $config['path_token'],
            'scope' => $config['scope'],
            'tenant' => $config['tenant'],
            'urlAPI' => $config['url_api'],
            'resource' => $config['resource'],
            'API_VERSION' => $config['api_version'],
            'authWithResource' => $config['auth_with_resource'],
            'defaultEndPointVersion' => $config['default_end_point_version'],
        ];
    }

    public function getPackagistName()
    {
        return 'thenetworg/oauth2-azure';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/thenetworg/oauth2-azure';
    }

    public function getProviderDisplayName()
    {
        return 'Azure';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\AzureClient';
    }
}
