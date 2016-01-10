<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
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
        'facebook',
        'github',
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
            // manually make sure "type" is there
            if (!isset($providerConfig['type'])) {
                throw new InvalidConfigurationException(sprintf(
                    'Your "knpu_oauth2_client.providers." config entry is missing the "type" key.',
                    $key
                ));
            }

            $type = $providerConfig['type'];
            unset($providerConfig['type']);
            if (!in_array($type, self::$supportedProviderTypes)) {
                throw new InvalidConfigurationException(sprintf(
                    'The "knpu_oauth2_client.providers" config "type" key "%s" is not supported. We support (%s)',
                    $type,
                    implode(', ', self::$supportedProviderTypes)
                ));
            }

            // process the configuration
            $tree = new TreeBuilder();
            $node = $tree->root('knpu_oauth2_client/providers/'.$key);
            $this->buildConfigurationForType($node, $type);
            $processor = new Processor();
            $config = $processor->process($tree->buildTree(), array($providerConfig));

            // call the specific configuration method
            $buildProviderMethod = sprintf('build%sProvider', ucfirst($type));
            $this->$buildProviderMethod($config, $container, $key);
        }
    }

    private function buildFacebookConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('graph_api_version')->isRequired()->end()
        ;
    }

    private function buildFacebookProvider(array $config, ContainerBuilder $container, $providerKey)
    {
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

    private function buildGithubConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('graph_api_version')->isRequired()->end()
        ;
    }

    private function buildGithubProvider(array $config, ContainerBuilder $container, $providerKey)
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

    public static function getAllSupportedTypes()
    {
        return self::$supportedProviderTypes;
    }

    public function buildConfigurationForType(NodeDefinition $node, $type)
    {
        $optionsNode = $node->children();
        $optionsNode
            ->scalarNode('client_id')->isRequired()->end()
            ->scalarNode('client_secret')->isRequired()->end()
            ->scalarNode('redirect_route')->isRequired()->end()
            ->arrayNode('redirect_params')
                ->prototype('scalar')
            ->end();

        // allow the specific providers to configure
        $buildConfigMethod = sprintf('build%sConfiguration', ucfirst($type));
        $this->$buildConfigMethod($optionsNode);
        $optionsNode->end();
    }
}
