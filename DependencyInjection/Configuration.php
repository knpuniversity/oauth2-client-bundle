<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('knpu_oauth2_client');

        $providersNode = $rootNode
            ->children()
            ->arrayNode('providers')
            ->addDefaultsIfNotSet()
            ->children();
        $this->configureFacebook($providersNode);
        $this->configureGithub($providersNode);
        $this->configureGoogle($providersNode);
        $providersNode
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }

    private function configureFacebook(NodeBuilder $builder)
    {
        $builder
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
            ->end();
    }

    private function configureGithub(NodeBuilder $builder)
    {
        $builder
            ->arrayNode('github')
                ->children()
                    ->scalarNode('client_id')->isRequired()->end()
                    ->scalarNode('client_secret')->isRequired()->end()
                    ->scalarNode('redirect_route')->isRequired()->end()
                    ->arrayNode('redirect_params')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end();
    }


    private function configureGoogle(NodeBuilder $builder)
    {
        $builder
            ->arrayNode('google')
                ->children()
                    ->scalarNode('client_id')->isRequired()->end()
                    ->scalarNode('client_secret')->isRequired()->end()
                    ->scalarNode('redirect_route')->isRequired()->end()
                    ->scalarNode('hosted_domain')->isRequired()->end()
                    ->scalarNode('access_type')->end()
                    ->arrayNode('redirect_params')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end();
    }
}
