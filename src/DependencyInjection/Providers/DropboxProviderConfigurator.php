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

class DropboxProviderConfigurator implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node)
    {
        // no custom options
    }

    public function getProviderClass(array $config)
    {
        return 'Stevenmaguire\OAuth2\Client\Provider\Dropbox';
    }

    public function getProviderOptions(array $config)
    {
        return [
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        ];
    }

    public function getPackagistName()
    {
        return 'stevenmaguire/oauth2-dropbox';
    }

    public function getLibraryHomepage()
    {
        return 'https://github.com/stevenmaguire/oauth2-dropbox';
    }

    public function getProviderDisplayName()
    {
        return 'Dropbox';
    }

    public function getClientClass(array $config)
    {
        return 'KnpU\OAuth2ClientBundle\Client\Provider\DropboxClient';
    }
}
