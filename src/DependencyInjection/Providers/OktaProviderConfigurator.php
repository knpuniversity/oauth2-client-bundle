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

class OktaProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('issuer')
                ->isRequired()
                ->info('Issuer URI from Okta')
                ->example('issuer: https://mycompany.okta.com/oauth2/default')
            ->end();
    }

    public function getProviderClass(array $config)
    {
        return 'Foxworth42\OAuth2\Client\Provider\Okta';
    }

    public function getProviderOptions(array $config)
    {
        $options = [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];

        if ($config['issuer']) {
            $options['issuer'] = $config['issuer'];
        }

        return $options;
    }

    public function getPackagistName()
    {
        return 'foxworth42/oauth2-okta';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/foxworth42/oauth2-okta';
    }

    public function getProviderDisplayName()
    {
        return 'Okta';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\OktaClient';
    }
}
