<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\Auth0ProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\AzureProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\BitbucketProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\DigitalOceanProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\DribbbleProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\DropboxProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\DrupalProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\EveOnlineProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\FacebookProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\GenericProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\GithubProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\GitlabProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\GoogleProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\HeadHunterProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\InstagramProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\LinkedInProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\OdnoklassnikiProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\ProviderConfiguratorInterface;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\SlackProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\VimeoProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\VKontakteProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\YahooProviderConfigurator;
use KnpU\OAuth2ClientBundle\DependencyInjection\Providers\YandexProviderConfigurator;
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
    /** @var bool */
    private $checkExternalClassExistence;

    /** @var array */
    private $configurators = [];

    /** @var array */
    private static $supportedProviderTypes = [
        'auth0' => Auth0ProviderConfigurator::class,
        'azure' => AzureProviderConfigurator::class,
        'digital_ocean' => DigitalOceanProviderConfigurator::class,
        'dribbble' => DribbbleProviderConfigurator::class,
        'dropbox' => DropboxProviderConfigurator::class,
        'drupal' => DrupalProviderConfigurator::class,
        'facebook' => FacebookProviderConfigurator::class,
        'headhunter' => HeadHunterProviderConfigurator::class,
        'github' => GithubProviderConfigurator::class,
        'gitlab' => GitlabProviderConfigurator::class,
        'linkedin' => LinkedInProviderConfigurator::class,
        'google' => GoogleProviderConfigurator::class,
        'eve_online' => EveOnlineProviderConfigurator::class,
        'instagram' => InstagramProviderConfigurator::class,
        'vkontakte' => VKontakteProviderConfigurator::class,
        'bitbucket' => BitbucketProviderConfigurator::class,
        'odnoklassniki' => OdnoklassnikiProviderConfigurator::class,
        'slack' => SlackProviderConfigurator::class,
        'yandex' => YandexProviderConfigurator::class,
        'vimeo' => VimeoProviderConfigurator::class,
        'yahoo' => YahooProviderConfigurator::class,
        'generic' => GenericProviderConfigurator::class,
    ];

    /**
     * KnpUOAuth2ClientExtension constructor.
     *
     * @param bool $checkExternalClassExistence
     */
    public function __construct($checkExternalClassExistence = true)
    {
        $this->checkExternalClassExistence = $checkExternalClassExistence;
    }

    /**
     * Load the bundle configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $clientConfigurations = $config['clients'];

        $clientServiceKeys = [];
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
            $node = $tree->root('knpu_oauth2_client/clients/' . $key);
            $this->buildConfigurationForType($node, $type);
            $processor = new Processor();
            $config = $processor->process($tree->buildTree(), [$clientConfig]);

            $configurator = $this->getConfigurator($type);

            // hey, we should add the provider/client service!
            $clientServiceKey = $this->configureProviderAndClient(
                $container,
                $type,
                $key,
                $configurator->getProviderClass($config),
                $configurator->getClientClass($config),
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
     * @param string $clientClass   Class to use for the Client
     * @param string $packageName   Packagist package name required
     * @param array $options        Options passed to when constructing the provider
     * @param string $redirectRoute Route name for the redirect URL
     * @param array $redirectParams Route params for the redirect URL
     * @param bool $useState
     * @return string The client service id
     */
    private function configureProviderAndClient(ContainerBuilder $container, $providerType, $providerKey, $providerClass, $clientClass, $packageName, array $options, $redirectRoute, array $redirectParams, $useState)
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

        $providerDefinition->setFactory([
            new Reference('knpu.oauth2.provider_factory'),
            'createProvider',
        ]);

        $providerDefinition->setArguments([
            $providerClass,
            $options,
            $redirectRoute,
            $redirectParams,
        ]);

        $clientServiceKey = sprintf('knpu.oauth2.client.%s', $providerKey);
        $clientDefinition = $container->register(
            $clientServiceKey,
            $clientClass
        );
        $clientDefinition->setArguments([
            new Reference($providerServiceKey),
            new Reference('request_stack'),
        ]);

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
     * Overridden so the alias isn't "knp_uo_auth2_client".
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
