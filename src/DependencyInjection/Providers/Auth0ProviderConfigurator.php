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
            ->scalarNode('custom_domain')
                ->defaultNull()
                ->info('Your custom/definite Auth0 domain, e.g. "login.mycompany.com". Set this if you use Auth0\'s Custom Domain feature. The "account" and "region" parameters will be ignored in this case.')
            ->end()
            ->scalarNode('account')
                ->defaultNull()
                ->info('Your Auth0 domain/account, e.g. "mycompany" if your domain is "mycompany.auth0.com"')
            ->end()
            ->scalarNode('region')
                ->defaultNull()
                ->info('Your Auth0 region, e.g. "eu" if your tenant is in the EU.')
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
            'customDomain' => $config['custom_domain'],
            'account' => $config['account'],
            'region' => $config['region'],
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
