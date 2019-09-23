<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\app;

use GuzzleHttp\Client;
use KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new KnpUOAuth2ClientBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->setDefinition('test.http_client', new Definition(Client::class, [
                [
                    'uri' => 'foo',
                ],
            ]));

            $container->loadFromExtension('framework', [
                'secret' => 'this is a cool bundle. Shhh..., it\'s a secret...',
                'router' => [
                    'resource' => __DIR__ . '/routing.yml',
                ],
                // turn this off - otherwise we need doctrine/annotation
                // the change that required this was in Symfony 3.2.0
                'annotations' => Kernel::VERSION_ID >= 30200 ? false : [],
            ]);

            $container->loadFromExtension('knpu_oauth2_client', [
                'http_client' => 'test.http_client',
                'clients' => [
                    'my_facebook' => [
                        'type' => 'facebook',
                        'client_id' => 'FOOO',
                        'client_secret' => 'BAR',
                        'graph_api_version' => 'v2.5',
                        'redirect_route' => 'my_test_route',
                    ],
                ],
            ]);
        });
    }

    public function getCacheDir(): string
    {
        if (method_exists($this, 'getProjectDir')) {
            return $this->getProjectDir() . '/tests/app/cache/' . $this->getEnvironment();
        }

        return parent::getCacheDir();
    }

    public function getLogDir(): string
    {
        if (method_exists($this, 'getProjectDir')) {
            return $this->getProjectDir() . '/tests/app/cache/' . $this->getEnvironment();
        }

        return parent::getLogDir();
    }
}
