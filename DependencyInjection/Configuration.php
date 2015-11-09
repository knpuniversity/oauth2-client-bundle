<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('knpu_oauth2_client');

        $rootNode
            ->children()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('facebook')
                            ->children()
                                ->scalarNode('client_id')->isRequired()->end()
                                ->scalarNode('client_secret')->isRequired()->end()
                                ->scalarNode('graph_api_version')->isRequired()->end()
                                ->scalarNode('redirect_route')->isRequired()->end()
                                ->arrayNode('redirect_params')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
