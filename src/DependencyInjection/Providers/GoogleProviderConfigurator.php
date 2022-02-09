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

class GoogleProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('access_type')
                ->defaultNull()
                ->info('Optional value for sending access_type parameter. More detail: https://developers.google.com/identity/protocols/OpenIDConnect#authenticationuriparameters')
            ->end()
            ->scalarNode('hosted_domain')
                ->defaultNull()
                ->info('Optional value for sending hd parameter. More detail: https://developers.google.com/identity/protocols/OpenIDConnect#hd-param')
            ->end()
            ->arrayNode('user_fields')
                ->prototype('scalar')->end()
                ->info('Optional value for additional fields to be requested from the user profile. If set, these values will be included with the defaults. More details: https://developers.google.com/+/web/api/rest/latest/people')
            ->end()
            ->booleanNode('use_oidc_mode')->defaultFalse()
                ->info('Optional value if you don\'t want or need to enable Google+ API access.')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'League\OAuth2\Client\Provider\Google';
    }

    public function getProviderOptions(array $config)
    {
        $options = [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];

        if ($config['access_type']) {
            $options['accessType'] = $config['access_type'];
        }

        if ($config['hosted_domain']) {
            $options['hostedDomain'] = $config['hosted_domain'];
        }

        if (!empty($config['user_fields'])) {
            $options['userFields'] = $config['user_fields'];
        }

        if (!empty($config['use_oidc_mode'])) {
            $options['useOidcMode'] = $config['use_oidc_mode'];
        }

        return $options;
    }

    public function getPackagistName()
    {
        return 'league/oauth2-google';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/thephpleague/oauth2-google';
    }

    public function getProviderDisplayName()
    {
        return 'Google';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient';
    }
}
