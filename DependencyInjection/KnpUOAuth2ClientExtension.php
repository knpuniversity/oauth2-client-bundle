<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
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

    static private $supportedProviderTypes = array(
        'facebook' => 'configureFacebook',
        'github' => 'configureGithub',
    );

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

        foreach ($providers as $key => $providerConfig) {
            // manual validation
            $requiredConfig = array('type', 'client_id', 'client_secret', 'redirect_route');
            foreach ($requiredConfig as $requiredConfigKey) {
                $this->validateRequiredProviderConfig($providerConfig, $requiredConfigKey, $key);
            }

            if (!isset($providerConfig['redirect_params'])) {
                $providerConfig['redirect_params'] = array();
            }

            if (!is_array($providerConfig['redirect_params'])) {
                throw new InvalidConfigurationException(sprintf(
                    'Your "knpu_oauth2_client.providers.%s.redirect_params" config must be an array. Currently, it is a %s',
                    $key,
                    gettype($providerConfig['redirect_params'])
                ));
            }

            $type = $providerConfig['type'];
            unset($providerConfig['type']);
            if (!isset(self::$supportedProviderTypes[$type])) {
                throw new InvalidConfigurationException(sprintf(
                    'The "knpu_oauth2_client.providers" config "type" key "%s" is not supported. We support (%s)',
                    $type,
                    implode(', ', array_keys(self::$supportedProviderTypes))
                ));
            }

            // call the specific configuration method
            $method = self::$supportedProviderTypes[$type];
            $definition = $this->$method($providerConfig, $container, $key);
        }
    }

    private function configureFacebook(array $config, ContainerBuilder $container, $providerKey)
    {
        $this->validateRequiredProviderConfig($config, 'graph_api_version', $providerKey);

        $options = array(
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'graphApiVersion' => $config['graph_api_version'],
        );

        $this->configureProvider(
            $container,
            'facebook',
            $providerKey,
            'League\OAuth2\Client\Provider\Facebook',
            'league/oauth2-facebook',
            $options,
            $config['redirect_route'],
            $config['redirect_params']
        );
    }

    private function configureGithub(array $config, ContainerBuilder $container, $providerKey)
    {
        $options = array(
            'clientId' => $config['client_id'],
            'clientSecret' => $config['client_secret'],
        );

        $this->configureProvider(
            $container,
            'github',
            $providerKey,
            'League\OAuth2\Client\Provider\Github',
            'league/oauth2-github',
            $options,
            $config['redirect_route'],
            $config['redirect_params']
        );
    }

    /**
     * @param ContainerBuilder $container
     * @param string $providerType  The "type" used in the config - e.g. "facebook"
     * @param string $providerKey   The config key used for this - e.g. "facebook_client", "my_facebook"
     * @param string $providerClass Provider class
     * @param string $packageName   Packagist package name required
     * @param array $options        Options passed to when constructing the provider
     * @param string $redirectRoute Route name for the redirect URL
     * @param array $redirectParams Route params for the redirect URL
     */
    private function configureProvider(ContainerBuilder $container, $providerType, $providerKey, $providerClass, $packageName, array $options, $redirectRoute, array $redirectParams)
    {
        if ($this->checkExternalClassExistence && !class_exists($providerClass)) {
            throw new \LogicException(sprintf(
                'Run `composer require %s` in order to use the "%s" OAuth provider.',
                $packageName,
                $providerType
            ));
        }

        $definition = $container->register(
            sprintf('knpu.oauth2.%s', $providerKey),
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

    private function validateRequiredProviderConfig(array $providerConfig, $requiredConfigKey, $providerKey)
    {
        if (!isset($providerConfig[$requiredConfigKey])) {
            throw new InvalidConfigurationException(sprintf(
                'Your "knpu_oauth2_client.providers.%s" config entry is missing a "%s" key.',
                $providerKey,
                $requiredConfigKey
            ));
        }
    }
}
