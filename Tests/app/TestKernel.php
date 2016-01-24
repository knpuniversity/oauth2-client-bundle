<?php

namespace KnpU\OAuth2ClientBundle\Tests\app;

use KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new KnpUOAuth2ClientBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function(ContainerBuilder $container) {
            $container->loadFromExtension('framework', array(
                'secret' => 'this is a cool bundle. Shhh..., it\'s a secret...',
                'router' => array(
                    'resource' => __DIR__.'/routing.yml',
                ),
            ));

            $container->loadFromExtension('knpu_oauth2_client', array(
                'providers' => array(
                    'my_facebook' => array(
                        'type' => 'facebook',
                        'client_id' => 'FOOO',
                        'client_secret' => 'BAR',
                        'graph_api_version' => 'v2.5',
                        'redirect_route' => 'my_test_route'
                    ),
                ),
            ));
        });
    }
}
