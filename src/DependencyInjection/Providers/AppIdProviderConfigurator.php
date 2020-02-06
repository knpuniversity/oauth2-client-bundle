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
 * Class AppIdProviderConfigurator.
 *
 * @author Dzianis Kotau <jampire.blr@gmail.com>
 */
class AppIdProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('base_auth_uri')
                ->isRequired()
                ->info('IBM App ID base URL. For example, "https://us-south.appid.cloud.ibm.com/oauth/v4". More details at https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started')
                ->example('base_auth_uri: \'%env(OAUTH_APPID_BASE_AUTH_URI)%\'')
            ->end()
            ->scalarNode('tenant_id')
                ->isRequired()
                ->info('IBM App ID service tenant ID. For example, "1234-5678-abcd-efgh". More details at https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started')
                ->example('tenant_id: \'%env(OAUTH_APPID_TENANT_ID)%\'')
            ->end()
            ->scalarNode('idp')
                ->defaultValue('saml')
                ->info('Identity Provider code. Defaults to "saml". More details at https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started')
                ->example('idp: \'%env(OAUTH_APPID_IDP)%\'')
            ->end()
        ;
    }

    public function getProviderClass(array $configuration)
    {
        return 'Jampire\OAuth2\Client\Provider\AppIdProvider';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\AppIdClient';
    }

    public function getProviderOptions(array $configuration)
    {
        return [
            'clientId' => $configuration['client_id'],
            'clientSecret' => $configuration['client_secret'],
            'baseAuthUri' => $configuration['base_auth_uri'],
            'tenantId' => $configuration['tenant_id'],
            'redirectRoute' => $configuration['redirect_route'],
            'idp' => $configuration['idp'],
        ];
    }

    public function getPackagistName()
    {
        return 'jampire/oauth2-appid';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/Jampire/oauth2-appid';
    }

    public function getProviderDisplayName()
    {
        return 'AppID';
    }
}
