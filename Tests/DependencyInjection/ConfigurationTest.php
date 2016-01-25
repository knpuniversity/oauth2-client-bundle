<?php

namespace KnpU\OAuth2ClientBundle\Tests\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideConfigurationTests
     */
    public function testDefaultConfig(array $startingConfig, $expectedConfig)
    {
        $expectsException = false === $expectedConfig;

        $processor = new Processor();
        try {
            $config = $processor->processConfiguration(
                new Configuration(true), array($startingConfig)
            );

            if ($expectsException) {
                $this->fail('Configuration did not throw an exception!');
            }

            $this->assertEquals(
                $expectedConfig,
                $config
            );
        } catch (\Exception $e) {
            // this is expected... unless it's not
            if (!$expectsException) {
                throw $e;
            }
        }
    }

    public function provideConfigurationTests()
    {
        $tests = array();

        $tests[] = array(
            array(),
            array('clients' => array())
        );

        $fbConfig = array(
            'type' => 'facebook',
            'client_id' => 'ABC',
            'client_secret' => '123',
            'graph_api_version' => '2.3',
            'redirect_route' => 'my_route',
            'redirect_params' => array('foo' => 'bars')
        );
        $tests[] = array(
            array('clients' => array('facebook1' => $fbConfig)),
            array('clients' => array('facebook1' => $fbConfig)),
        );

        $tests[] = array(
            array('clients' => array('facebook2' => 'some_string')),
            false
        );

        return $tests;
    }
}
