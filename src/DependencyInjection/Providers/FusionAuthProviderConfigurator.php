<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use JerryHopper\OAuth2\Client\Provider\FusionAuth;
use KnpU\OAuth2ClientBundle\Client\Provider\FusionAuthClient;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class FusionAuthProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('auth_server_url')
                ->isRequired()
                ->info('FusionAuth Server URL, no trailing slash')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return FusionAuth::class;
    }

    public function getProviderOptions(array $config)
    {
        $auth_server_url = $config['auth_server_url'];

        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'urlAuthorize' => $auth_server_url.'/oauth2/authorize',
            'urlAccessToken' => $auth_server_url.'/oauth2/token',
            'urlResourceOwnerDetails' => $auth_server_url.'/oauth2/userinfo',
        ];
    }

    public function getPackagistName()
    {
        return 'jerryhopper/oauth2-fusionauth';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/jerryhopper/oauth2-fusionauth';
    }

    public function getProviderDisplayName()
    {
        return 'FusionAuth';
    }

    public function getClientClass(array $config)
    {
        return FusionAuthClient::class;
    }
}
