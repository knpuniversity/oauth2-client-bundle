<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Jampire\OAuth2\Client\Provider\AppIdProvider;
use KnpU\OAuth2ClientBundle\Client\Provider\AppIdClient;

/**
 * Class AppIdProviderConfigurator
 *
 * @author Dzianis Kotau <jampire.blr@gmail.com>
 * @package KnpU\OAuth2ClientBundle\DependencyInjection\Providers
 */
class AppIdProviderConfigurator implements ProviderConfiguratorInterface
{

    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('base_auth_uri')
                ->isRequired()
                ->info('IBM App ID base URL. More detail: https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started')
                ->example('base_auth_uri: https://us-south.appid.cloud.ibm.com/oauth/v4')
            ->end()
            ->scalarNode('tenant_id')
                ->isRequired()
                ->info('IBM App ID service tenant ID. More detail: https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started')
                ->example('tenant_id: 1234-5678-abcd-efgh')
            ->end()
            ->scalarNode('idp')
                ->defaultValue('saml')
                ->info('Identity Provider code. More detail: https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started')
                ->example('idp: saml')
            ->end()
        ;
    }

    public function getProviderClass(array $configuration)
    {
        return AppIdProvider::class;
    }

    public function getClientClass(array $config)
    {
        return AppIdClient::class;
    }

    public function getProviderOptions(array $configuration)
    {
        return [
            'clientId' => $configuration['client_id'],
            'clientSecret' => $configuration['client_secret'],
            'baseAuthUri' => $configuration['base_auth_uri'],
            'tenantId' => $configuration['tenant_id'],
            'redirectRouteName' => $configuration['redirect_route'],
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
