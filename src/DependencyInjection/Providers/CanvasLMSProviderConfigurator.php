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

class CanvasLMSProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('canvas_instance_url')
                ->isRequired()
                ->info('URL of Canvas Instance (e.g. https://canvas.instructure.com)')
            ->end()
            ->scalarNode('purpose')
                ->defaultValue('')
                ->info(
                    'This can be used to help the user identify which instance of an application this token is '
                    .'for. For example, a mobile device application could provide the name of the device.'
                )

            ->end()
        ;
    }

    public function getProviderClass(array $config)
    {
        return 'smtech\OAuth2\Client\Provider\CanvasLMS';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'purpose' => $config['purpose'],
            'canvasInstanceUrl' => $config['canvas_instance_url'],
        ];
    }

    public function getPackagistName()
    {
        return 'smtech/oauth2-canvaslms';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/smtech/oauth2-canvaslms';
    }

    public function getProviderDisplayName()
    {
        return 'CanvasLMS';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\CanvasLMSClient';
    }
}
