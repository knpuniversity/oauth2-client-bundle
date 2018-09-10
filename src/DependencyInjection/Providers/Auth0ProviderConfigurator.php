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

class Auth0ProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('account')
                ->isRequired()
                ->info('Your Auth0 domain/account, e.g. "mycompany" if your domain is "mycompany.auth0.com"')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'Riskio\OAuth2\Client\Provider\Auth0';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'account' => $config['account'],
        ];
    }

    public function getPackagistName()
    {
        return 'riskio/oauth2-auth0';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/RiskioFr/oauth2-auth0';
    }

    public function getProviderDisplayName()
    {
        return 'Auth0';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\Auth0Client';
    }
}
