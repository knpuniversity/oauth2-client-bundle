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

class SalesforceProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('domain')
                ->defaultValue('')
                ->info('Custom Salesforce domain. Default domain is https://login.salesforce.com')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'Stevenmaguire\OAuth2\Client\Provider\Salesforce';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'domain' => $config['domain'],
        ];
    }

    public function getPackagistName()
    {
        return 'stevenmaguire/oauth2-salesforce';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/stevenmaguire/oauth2-salesforce';
    }

    public function getProviderDisplayName()
    {
        return 'Salesforce';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\SalesforceClient';
    }
}
