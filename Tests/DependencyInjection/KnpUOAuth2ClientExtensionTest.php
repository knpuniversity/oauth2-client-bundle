<?php

namespace KnpU\OAuth2ClientBundle\Tests\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\KnpUOAuth2ClientExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class KnpUOAuth2ClientExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $configuration;

    public function testNoProvidersMakesNoServices()
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
        $config = array('providers' => array('facebook_client' => array(
            'type' => 'facebook',
            'client_id' => 'CLIENT_ID',
            'client_secret' => 'SECRET',
            'graph_api_version' => 'API_VERSION',
            'redirect_route' => 'the_route_name',
            'redirect_params' => array('route_params' => 'foo')
        )));
        $loader->load(array($config), $this->configuration);

        $definition = $this->configuration->getDefinition('knpu.oauth2.facebook_client');
        $factory = $definition->getFactory();
        // make sure the factory is correct
        $this->assertEquals(
            array(new Reference('knpu.oauth.provider_factory'), 'createProvider'),
            $factory
        );
        $this->assertEquals(
            array(
                'League\OAuth2\Client\Provider\Facebook',
                array('clientId' => 'CLIENT_ID', 'clientSecret' => 'SECRET', 'graphApiVersion' => 'API_VERSION'),
                'the_route_name',
                array('route_params' => 'foo')
            ),
            $definition->getArguments()
        );
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @dataProvider provideBadConfiguration
     */
    public function testBadProvidersConfiguration(array $badProvidersConfig)
    {
        $this->configuration = new ContainerBuilder();
        $loader = new KnpUOAuth2ClientExtension(false);
        $config = array('providers' => $badProvidersConfig);
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

    protected function tearDown()
    {
        unset($this->configuration);
    }
}
