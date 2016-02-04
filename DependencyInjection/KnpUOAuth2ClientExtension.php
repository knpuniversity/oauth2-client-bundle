<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\ProviderConfiguratorInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
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

    private $configurators = array();

    static private $supportedProviderTypes = array(
        'facebook' => 'KnpU\OAuth2ClientBundle\DependencyInjection\Providers\FacebookProviderConfigurator',
        'github' => 'KnpU\OAuth2ClientBundle\DependencyInjection\Providers\GithubProviderConfigurator',
        'linkedin' => 'KnpU\OAuth2ClientBundle\DependencyInjection\Providers\LinkedInProviderConfigurator',
        'google' => 'KnpU\OAuth2ClientBundle\DependencyInjection\Providers\GoogleProviderConfigurator',
        'vk' => 'KnpU\OAuth2ClientBundle\DependencyInjection\Providers\VkProviderConfigurator',
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

        $clientConfigurations = $config['clients'];

        $clientServiceKeys = array();
        foreach ($clientConfigurations as $key => $clientConfig) {
            // manually make sure "type" is there
            if (!isset($clientConfig['type'])) {
                throw new InvalidConfigurationException(sprintf(
                    'Your "knpu_oauth2_client.clients." config entry is missing the "type" key.',
                    $key
                ));
            }

            $type = $clientConfig['type'];
            unset($clientConfig['type']);
            if (!isset(self::$supportedProviderTypes[$type])) {
                throw new InvalidConfigurationException(sprintf(
                    'The "knpu_oauth2_client.clients" config "type" key "%s" is not supported. We support (%s)',
                    $type,
                    implode(', ', self::$supportedProviderTypes)
                ));
            }

            // process the configuration
            $tree = new TreeBuilder();
            $node = $tree->root('knpu_oauth2_client/clients/'.$key);
            $this->buildConfigurationForType($node, $type);
            $processor = new Processor();
            $config = $processor->process($tree->buildTree(), array($clientConfig));

            $configurator = $this->getConfigurator($type);

            // hey, we should add the provider/client service!
            $clientServiceKey = $this->configureProviderAndClient(
                $container,
                $type,
                $key,
                $configurator->getProviderClass(),
                $configurator->getPackagistName(),
                $configurator->getProviderOptions($config),
                $config['redirect_route'],
                $config['redirect_params'],
                $config['use_state']
            );

            $clientServiceKeys[$key] = $clientServiceKey;
        }

        $container->getDefinition('knpu.oauth2.registry')
            ->replaceArgument(1, $clientServiceKeys);
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
     * @param bool $useState
     * @return string The client service id
     */
    private function configureProviderAndClient(ContainerBuilder $container, $providerType, $providerKey, $providerClass, $packageName, array $options, $redirectRoute, array $redirectParams, $useState)
    {
        if ($this->checkExternalClassExistence && !class_exists($providerClass)) {
            throw new \LogicException(sprintf(
                'Run `composer require %s` in order to use the "%s" OAuth provider.',
                $packageName,
                $providerType
            ));
        }

        $providerServiceKey = sprintf('knpu.oauth2.provider.%s', $providerKey);

        $providerDefinition = $container->register(
            $providerServiceKey,
            $providerClass
        );
        $providerDefinition->setPublic(false);

        $providerDefinition->setFactory(array(
            new Reference('knpu.oauth2.provider_factory'),
            'createProvider'
        ));

        $providerDefinition->setArguments(array(
            $providerClass,
            $options,
            $redirectRoute,
            $redirectParams
        ));

        $clientServiceKey = sprintf('knpu.oauth2.client.%s', $providerKey);
        $clientDefinition = $container->register(
            $clientServiceKey,
            'KnpU\OAuth2ClientBundle\Client\OAuth2Client'
        );
        $clientDefinition->setArguments(array(
            new Reference($providerServiceKey),
            new Reference('request_stack')
        ));

        // if stateless, do it!
        if (!$useState) {
            $clientDefinition->addMethodCall('setAsStateless');
        }

        return $clientServiceKey;
    }

    public static function getAllSupportedTypes()
    {
        return array_keys(self::$supportedProviderTypes);
    }

    /**
    * @param string $type
    * @return ProviderConfiguratorInterface
    */
   public function getConfigurator($type)
   {
       if (!isset($this->configurators[$type])) {
           $class = self::$supportedProviderTypes[$type];

           $this->configurators[$type] = new $class();
       }

       return $this->configurators[$type];
   }

    /**
     * Overridden so the alias isn't "knp_uo_auth2_client"
     *
     * @return string
     */
    public function getAlias()
    {
        return 'knpu_oauth2_client';
    }

    private function buildConfigurationForType(NodeDefinition $node, $type)
    {
        $optionsNode = $node->children();
        $optionsNode
            ->scalarNode('client_id')->isRequired()->end()
            ->scalarNode('client_secret')->isRequired()->end()
            ->scalarNode('redirect_route')->isRequired()->end()
            ->arrayNode('redirect_params')
                ->prototype('scalar')->end()
            ->end()
            ->booleanNode('use_state')->defaultValue(true)->end()
        ;

        // allow the specific provider to add more options
        $this->getConfigurator($type)
            ->buildConfiguration($optionsNode);
        $optionsNode->end();
    }
}
