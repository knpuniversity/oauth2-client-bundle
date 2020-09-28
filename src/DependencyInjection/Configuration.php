<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('knpu_oauth2_client');
        $rootNode = method_exists($treeBuilder, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root('knpu_oauth2_client');

        $rootNode
            ->children()
                ->scalarNode('http_client')
                    ->defaultNull()
                    ->info('Service id of HTTP client to use (must implement GuzzleHttp\ClientInterface)')
                ->end()
                ->arrayNode('http_client_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('timeout')->min(0)->end()
                        ->scalarNode('proxy')->end()
                        ->booleanNode('verify')->info('Use only with proxy option set')->end()
                    ->end()
                ->end()
                ->arrayNode('clients')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('variable')
                    ->prototype('array')
                        ->useAttributeAsKey('variable')
                        ->prototype('variable')->end()
                    ->end()
                ->end()
            ->end()
            ->validate()
                ->ifTrue(function ($v) {
                    return isset($v['http_client_options'], $v['http_client']) && !empty($v['http_client_options']);
                })
                ->thenInvalid('You cannot use both "http_client_options" and "http_client" at the same time under "knpu_oauth2_client".')
            ->end()
        ;

        return $treeBuilder;
    }
}
