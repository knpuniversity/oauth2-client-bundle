<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class KnpUOAuth2ClientExtension extends Extension
{
    /**
     * @var bool
     */
    private $checkExternalClassExistence;

    public function __construct($checkExternalClassExistence = true)
    {
        $this->checkExternalClassExistence = $checkExternalClassExistence;
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $providers = $config['providers'];
        if (isset($providers['facebook'])) {
            $providerConfig = $providers['facebook'];
            $class = 'League\OAuth2\Client\Provider\Facebook';
            if ($this->checkExternalClassExistence && !class_exists($class)) {
                throw new \LogicException('Run `composer require league/oauth2-facebook` in order to use the "facebook" OAuth provider.');
            }

            $options = array(
                'clientId' => $providerConfig['client_id'],
                'clientSecret' => $providerConfig['client_secret'],
                'graphApiVersion' => $providerConfig['graph_api_version'],
            );

            $definition = $container->register(
                'knpu.oauth.facebook_provider',
                $class
            );
            $definition->setFactory(array(
                new Reference('knpu.oauth.provider_factory'),
                'createProvider'
            ));
            $definition->setArguments(array(
                $class,
                $options,
                $providerConfig['redirect_route'],
                $providerConfig['redirect_params']
            ));
        }
    }
}
