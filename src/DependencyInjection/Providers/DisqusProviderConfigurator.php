<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Antalaron\DisqusOAuth2\Disqus;
use KnpU\OAuth2ClientBundle\Client\Provider\DisqusClient;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class DisqusProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // no custom options
    }

    public function getProviderClass(array $config)
    {
        return Disqus::class;
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];
    }

    public function getPackagistName()
    {
        return 'antalaron/oauth2-disqus';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/antalaron/oauth2-disqus';
    }

    public function getProviderDisplayName()
    {
        return 'Disqus';
    }

    public function getClientClass(array $config)
    {
        return DisqusClient::class;
    }
}
