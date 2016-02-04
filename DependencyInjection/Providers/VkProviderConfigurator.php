<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class VkProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // no custom options
    }

    public function getProviderClass()
    {
        return 'J4k\OAuth2\Client\Provider\Vkontakte';
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
        return 'j4k/oauth2-vkontakte';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/j4k/oauth2-vkontakte';
    }

    public function getProviderDisplayName()
    {
        return 'VK';
    }

    public function getCustomClientClass()
    {
        return;
    }
}