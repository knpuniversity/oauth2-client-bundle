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

/**
 * GitlabProviderConfigurator.
 *
 * @author Niels Keurentjes <niels.keurentjes@omines.com>
 */
class GitlabProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('domain')
                ->info('Base installation URL, modify this for self-hosted instances')
                ->defaultValue('https://gitlab.com')
                ->cannotBeEmpty()
            ->end();
    }

    public function getProviderClass(array $config)
    {
        return 'Omines\OAuth2\Client\Provider\Gitlab';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'domain' => $config['domain'],
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];
    }

    public function getPackagistName()
    {
        return 'omines/oauth2-gitlab';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/omines/oauth2-gitlab';
    }

    public function getProviderDisplayName()
    {
        return 'GitLab';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\GitlabClient';
    }
}
