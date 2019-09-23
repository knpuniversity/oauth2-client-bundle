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

class LinkedInProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->integerNode('api_version')
                ->defaultNull()
                ->info('Optional value to specify Linkedin\'s API version to use. As the time of writing, v1 is still used by default by league/oauth2-linkedin.')
            ->end()
            ->arrayNode('fields')
                ->prototype('scalar')->end()
                ->info('Optional value to specify fields to be requested from the profile. Since Linkedin\'s API upgrade from v1 to v2, fields and authorizations policy have been enforced. See https://docs.microsoft.com/en-us/linkedin/consumer/integrations/self-serve/sign-in-with-linkedin for more details.')
            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'League\OAuth2\Client\Provider\LinkedIn';
    }

    public function getProviderOptions(array $config)
    {
        $options = [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];

        if (null !== $config['api_version']) {
            $options['resourceOwnerVersion'] = $config['api_version'];
        }

        if (!empty($config['fields'])) {
            $options['fields'] = $config['fields'];
        }

        return $options;
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

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\LinkedInClient';
    }
}
