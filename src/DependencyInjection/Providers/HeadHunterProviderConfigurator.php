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

class HeadHunterProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('state')
                ->defaultNull()
                ->info('Optional value for CSRF Protection. https://github.com/hhru/api/blob/master/docs_eng/authorization.md')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'AlexMasterov\OAuth2\Client\Provider\HeadHunter';
    }

    public function getProviderOptions(array $config)
    {
        $options = [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret']
        ];

        if ($config['state']) {
            $options['state'] = $config['state'];
        }

        return $options;
    }

    public function getPackagistName()
    {
        return 'alexmasterov/oauth2-headhunter';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/AlexMasterov/oauth2-headhunter';
    }

    public function getProviderDisplayName()
    {
        return 'HeadHunter';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\HeadHunterClient';
    }
}
