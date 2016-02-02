<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class GoogleProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // todo - add the comments as help text, and render in README
        $node
            // Optional value for sending hd parameter. More detail: https://developers.google.com/accounts/docs/OAuth2Login#hd-param
            ->scalarNode('access_type')->end()
            // #Optional value for sending access_type parameter. More detail: https://developers.google.com/identity/protocols/OAuth2WebServer#offline
            ->scalarNode('hosted_domain')->end()
        ;
    }

    public function getProviderClass()
    {
        return 'League\OAuth2\Client\Provider\Google';
    }

    public function getProviderOptions(array $config)
    {
        return array(
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'accessType' => $config['access_type'],
            'hostedDomain' => $config['hosted_domain']
        );
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
}
