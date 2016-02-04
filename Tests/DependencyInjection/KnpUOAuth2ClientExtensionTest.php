<?php

namespace KnpU\OAuth2ClientBundle\Tests\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\KnpUOAuth2ClientExtension;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class KnpUOAuth2ClientExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $configuration;

    public function testNoClientMakesNoServices()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new KnpUOAuth2ClientExtension();
        $config = array();
        $loader->load(array($config), $this->configuration);

        $this->assertFalse($this->configuration->hasDefinition('knpu.oauth2.facebook_client'));
    }

    public function testFacebookProviderMakesService()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new KnpUOAuth2ClientExtension(false);
        $config = array('clients' => array('facebook' => array(
            'type' => 'facebook',
            'client_id' => 'CLIENT_ID',
            'client_secret' => 'SECRET',
            'graph_api_version' => 'API_VERSION',
            'redirect_route' => 'the_route_name',
            'redirect_params' => array('route_params' => 'foo')
        )));
        $loader->load(array($config), $this->configuration);

        $providerDefinition = $this->configuration->getDefinition('knpu.oauth2.provider.facebook');

        $factory = $providerDefinition->getFactory();
        // make sure the factory is correct
        $this->assertEquals(
            array(new Reference('knpu.oauth2.provider_factory'), 'createProvider'),
            $factory
        );

        $this->assertEquals(
            array(
                'League\OAuth2\Client\Provider\Facebook',
                array('clientId' => 'CLIENT_ID', 'clientSecret' => 'SECRET', 'graphApiVersion' => 'API_VERSION'),
                'the_route_name',
                array('route_params' => 'foo')
            ),
            $providerDefinition->getArguments()
        );

        $clientDefinition = $this->configuration->getDefinition('knpu.oauth2.client.facebook');
        $this->assertEquals(
            'KnpU\OAuth2ClientBundle\Client\OAuth2Client',
            $clientDefinition->getClass()
        );
        $this->assertEquals(
            [
                new Reference('knpu.oauth2.provider.facebook'),
                new Reference('request_stack'),
            ],
            $clientDefinition->getArguments()
        );
    }

    public function testGoogleProviderMakesService()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new KnpUOAuth2ClientExtension(false);
        $config = array('providers' => array('google' => array(
            'client_id' => 'CLIENT_ID',
            'client_secret' => 'SECRET',
            'redirect_route' => 'the_route_name',
            'redirect_params' => array('route_params' => 'foo'),
            'hosted_domain' => 'fakedomain.com',
            'access_type' => 'offline'
        )));
        $loader->load(array($config), $this->configuration);

        $definition = $this->configuration->getDefinition('knpu.oauth.google_provider');
        $factory = $definition->getFactory();
        // make sure the factory is correct
        $this->assertEquals(
            array(new Reference('knpu.oauth.provider_factory'), 'createProvider'),
            $factory
        );

        $this->assertEquals(
            array(
                'League\OAuth2\Client\Provider\Google',
                array(
                    'clientId' => 'CLIENT_ID',
                    'clientSecret' => 'SECRET',
                    'hostedDomain' => 'fakedomain.com',
                    'access_type' => 'offline'
                ),
                'the_route_name',
                array('route_params' => 'foo')
            ),
            $definition->getArguments()
        );
    }

    /**
     * @dataProvider provideTypesAndConfig
     */
    public function testAllClientsCreateDefinition($type, array $inputConfig)
    {
        $this->configuration = new ContainerBuilder();
        $loader = new KnpUOAuth2ClientExtension(false);
        $inputConfig['type'] = $type;
        $config = array('clients' => array('test_service' => $inputConfig));
        $loader->load(array($config), $this->configuration);

        $this->assertTrue($this->configuration->hasDefinition('knpu.oauth2.provider.test_service'));
        $this->assertTrue($this->configuration->hasDefinition('knpu.oauth2.client.test_service'));
    }

    public function provideTypesAndConfig()
    {
        $tests = array();
        $extension = new KnpUOAuth2ClientExtension();

        foreach (KnpUOAuth2ClientExtension::getAllSupportedTypes() as $type) {
            $configurator = $extension->getConfigurator($type);
            $tree = new TreeBuilder();
            $configNode = $tree->root('testing');
            $configurator->buildConfiguration($configNode->children(), $type);

            /** @var ArrayNode $arrayNode */
            $arrayNode = $tree->buildTree();
            $config = array(
                'client_id' => 'CLIENT_ID_TEST',
                'client_secret' => 'CLIENT_SECRET_TEST',
                'redirect_route' => 'go_there',
                'redirect_params' => array(),
                'use_state' => rand(0, 1) == 0
            );
            // loop through and assign some random values
            foreach ($arrayNode->getChildren() as $child) {
                /** @var NodeInterface $child */
                if ($child instanceof ArrayNode) {
                    $config[$child->getName()] = array();
                } else {
                    $config[$child->getName()] = rand();
                }
            }

            $tests[] = array($type, $config);
        }

        return $tests;
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @dataProvider provideBadConfiguration
     */
    public function testBadClientsConfiguration(array $badClientsConfig)
    {
        $this->configuration = new ContainerBuilder();
        $loader = new KnpUOAuth2ClientExtension(false);
        $config = array('clients' => $badClientsConfig);
        $loader->load(array($config), $this->configuration);
    }

    public function provideBadConfiguration()
    {
        $tests = array();

        $goodConfig = array(
            'type' => 'facebook',
            'client_id' => 'abc',
            'client_secret' => '123',
            'redirect_route' => 'foo_bar_route',
            'redirect_params' => array()
        );

        $badConfig1 = $goodConfig;
        unset($badConfig1['type']);
        $tests[] = array('facebook1' => $badConfig1);

        $badConfig2 = $goodConfig;
        unset($badConfig2['client_id']);
        $tests[] = array('facebook1' => $badConfig2);

        $badConfig3 = $goodConfig;
        unset($badConfig3['client_secret']);
        $tests[] = array('facebook1' => $badConfig3);

        $badConfig4 = $goodConfig;
        unset($badConfig4['redirect_uri']);
        $tests[] = array('facebook1' => $badConfig4);

        $badConfig5 = $goodConfig;
        unset($badConfig5['redirect_params']);
        $tests[] = array('facebook1' => $badConfig5);

        $badConfig6 = $goodConfig;
        $badConfig6['redirect_paras'] = 'NOT AN ARRAY';
        $tests[] = array('facebook1' => $badConfig6);

        $badConfig7 = $goodConfig;
        $badConfig7['type'] = 'fake_type';
        $tests[] = array('facebook1' => $badConfig7);

        return $tests;
    }

    public function testGetAllSupportedTypes()
    {
        $types = KnpUOAuth2ClientExtension::getAllSupportedTypes();

        $this->assertTrue(in_array('facebook', $types));
    }

    public function testGetAlias()
    {
        $extension = new KnpUOAuth2ClientExtension();
        $this->assertEquals('knpu_oauth2_client', $extension->getAlias());
    }

    protected function tearDown()
    {
        unset($this->configuration);
    }
}
