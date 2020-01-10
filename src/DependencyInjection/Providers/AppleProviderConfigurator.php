<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class AppleProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('team_id')
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('key_file_id')
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('key_file_path')
                ->cannotBeEmpty()
            ->end()
        ;
    }

    public function getProviderClass(array $configuration)
    {
        return 'League\OAuth2\Client\Provider\Apple';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\AppleClient';
    }

    public function getProviderOptions(array $configuration)
    {
        return [
            'clientId' => $configuration['client_id'],
            'teamId' => $configuration['team_id'],
            'keyFileId' => $configuration['key_file_id'],
            'keyFilePath' => $configuration['key_file_path'],
        ];
    }

    public function getPackagistName()
    {
        return 'patrickbussmann/oauth2-apple';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/patrickbussmann/oauth2-apple';
    }

    public function getProviderDisplayName()
    {
        return 'Apple';
    }
}
