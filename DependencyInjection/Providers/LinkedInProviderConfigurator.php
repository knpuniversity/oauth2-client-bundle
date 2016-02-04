<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class LinkedInProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // no custom options
    }

    public function getProviderClass()
    {
        return 'League\OAuth2\Client\Provider\LinkedIn';
    }

    public function getProviderOptions(array $config)
    {
        return array(
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        );
    }

    public function getPackagistName()
    {
        return 'league/oauth2-linkedin';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/thephpleague/oauth2-linkedin';
    }

    public function getProviderDisplayName()
    {
        return 'LinkedIn';
    }

    public function getCustomClientClass()
    {
        return;
    }
}