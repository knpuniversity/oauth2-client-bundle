<?php

namespace KnpU\OAuth2ClientBundle\Tests\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideConfigurationTests
     */
    public function testDefaultConfig(array $startingConfig, array $expectedConfig)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration(true), array($startingConfig)
        );

        $this->assertEquals(
            $expectedConfig,
            $config
        );
    }

    public function provideConfigurationTests()
    {
        $tests = array();

        $tests[] = array(
            array(),
            array('providers' => array())
        );

        $fbConfig = array(
            'client_id' => 'ABC',
            'client_secret' => '123',
            'graph_api_version' => '2.3',
            'redirect_route' => 'my_route',
            'redirect_params' => array('foo' => 'bars')
        );
        $tests[] = array(
            array('providers' => array('facebook' => $fbConfig)),
            array('providers' => array('facebook' => $fbConfig)),
        );

        return $tests;
    }
}
