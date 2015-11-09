<?php

namespace KnpU\OAuth2ClientBundle\Tests\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\KnpUOAuth2ClientExtension;
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

        $this->assertFalse($this->configuration->hasDefinition('knpu.oauth.facebook_provider'));
    }

    public function testFacebookProviderMakesService()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new KnpUOAuth2ClientExtension(false);
        $config = array('providers' => array('facebook' => array(
            'client_id' => 'CLIENT_ID',
            'client_secret' => 'SECRET',
            'graph_api_version' => 'API_VERSION',
            'redirect_route' => 'the_route_name',
            'redirect_params' => array('route_params' => 'foo')
        )));
        $loader->load(array($config), $this->configuration);

        $definition = $this->configuration->getDefinition('knpu.oauth.facebook_provider');
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

    protected function tearDown()
    {
        unset($this->configuration);
    }
}
