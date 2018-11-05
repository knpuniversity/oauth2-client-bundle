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

class DrupalProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('base_url')
                ->example("base_url: '%env(OAUTH_DRUPAL_BASE_URL)%'")
                ->isRequired()
                ->info('Drupal oAuth2 server URL')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'ChrisHemmings\OAuth2\Client\Provider\Drupal';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'baseUrl' => $config['base_url'],
        ];
    }

    public function getPackagistName()
    {
        return 'chrishemmings/oauth2-drupal';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/chrishemmings/oauth2-drupal';
    }

    public function getProviderDisplayName()
    {
        return 'Drupal';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\DrupalClient';
    }
}
