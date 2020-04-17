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

class BuddyProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('base_api_url')
                ->info('Base API URL, modify this for self-hosted instances')
                ->defaultValue('https://api.buddy.works')
                ->cannotBeEmpty()
            ->end();
    }

    public function getProviderClass(array $config)
    {
        return 'Buddy\OAuth2\Client\Provider\Buddy';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'baseApiUrl' => $config['base_api_url'],
        ];
    }

    public function getPackagistName()
    {
        return 'buddy-works/oauth2-client';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/buddy-works/oauth2-client';
    }

    public function getProviderDisplayName()
    {
        return 'Buddy';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\BuddyClient';
    }
}
