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
            $this->configureFacebook($providers['facebook'], $container);
        }
        if (isset($providers['github'])) {
            $this->configureGithub($providers['github'], $container);
        }
    }

    private function configureFacebook(array $config, ContainerBuilder $container)
    {
        $options = array(
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'graphApiVersion' => $config['graph_api_version'],
        );

        $this->configureProvider(
            $container,
            'facebook',
            'League\OAuth2\Client\Provider\Facebook',
            'league/oauth2-facebook',
            $options,
            $config['redirect_route'],
            $config['redirect_params']
        );
    }

    private function configureGithub(array $config, ContainerBuilder $container)
    {
        $options = array(
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        );

        $this->configureProvider(
            $container,
            'facebook',
            'League\OAuth2\Client\Provider\Github',
            'league/oauth2-github',
            $options,
            $config['redirect_route'],
            $config['redirect_params']
        );
    }

    private function configureProvider(ContainerBuilder $container, $name, $providerClass, $packageName, array $options, $redirectRoute, array $redirectParams)
    {
        if ($this->checkExternalClassExistence && !class_exists($providerClass)) {
            throw new \LogicException(sprintf(
                'Run `composer require %s` in order to use the "%s" OAuth provider.',
                $packageName,
                $name
            ));
        }

        $definition = $container->register(
            sprintf('knpu.oauth.%s_provider', $name),
            $providerClass
        );
        $definition->setFactory(array(
            new Reference('knpu.oauth.provider_factory'),
            'createProvider'
        ));
        $definition->setArguments(array(
            $providerClass,
            $options,
            $redirectRoute,
            $redirectParams
        ));
    }
}
