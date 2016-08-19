<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
                new Configuration(true), [$startingConfig]
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
        $tests = [];

        $tests[] = [
            [],
            ['clients' => []],
        ];

        $fbConfig = [
            'type' => 'facebook',
            'client_id' => 'ABC',
            'client_secret' => '123',
            'graph_api_version' => '2.3',
            'redirect_route' => 'my_route',
            'redirect_params' => ['foo' => 'bars'],
        ];
        $tests[] = [
            ['clients' => ['facebook1' => $fbConfig]],
            ['clients' => ['facebook1' => $fbConfig]],
        ];

        $tests[] = [
            ['clients' => ['facebook2' => 'some_string']],
            false,
        ];

        return $tests;
    }
}
