<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use KnpU\OAuth2ClientBundle\Client\Provider\InstagramClient;
use League\OAuth2\Client\Provider\Instagram;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class InstagramProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // no options...
    }

    public function getProviderClass(array $config)
    {
        return Instagram::class;
    }

    public function getProviderOptions(array $config)
    {
        return array(
            'clientId' => $config['clientId'],
            'clientSecret' => $config['clientSecret'],
        );
    }

    public function getPackagistName()
    {
        return 'league/oauth2-instagram';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/thephpleague/oauth2-instagram';
    }

    public function getProviderDisplayName()
    {
        return 'Instagram';
    }

    public function getClientClass(array $config)
    {
        return InstagramClient::class;
    }
}
