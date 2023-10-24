<?php

declare(strict_types=1);

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use KnpU\OAuth2ClientBundle\Client\Provider\PassageClient;
use League\OAuth2\Client\Provider\Passage;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class PassageProviderConfiguration implements ProviderConfiguratorInterface
{
    public function buildConfiguration(NodeBuilder $node): void
    {
        $node
            ->scalarNode('sub_domain')
                ->isRequired()
                ->info('Passage sub domain. For example, from passage url "https://example.withpassage.com" only "example" is required.')
                ->example('sub_domain: \'%env(OAUTH_PASSAGE_SUB_DOMAIN)%\'')
                ->example('sub_domain: \'example\'')
            ->end()
        ;
    }

    public function getProviderClass(array $configuration): string
    {
        return Passage::class;
    }

    public function getClientClass(array $config): string
    {
        return PassageClient::class;
    }

    public function getProviderOptions(array $configuration): array
    {
        return [
            'clientId' => $configuration['client_id'],
            'clientSecret' => $configuration['client_secret'],
            'subDomain' => $configuration['sub_domain'],
        ];
    }

    public function getPackagistName(): string
    {
        return 'malteschlueter/oauth2-passage';
    }

    public function getLibraryHomepage(): string
    {
        return 'https://github.com/malteschlueter/oauth2-passage';
    }

    public function getProviderDisplayName(): string
    {
        return 'Passage';
    }
}
