<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\tests\DependencyInjection;

use KnpU\OAuth2ClientBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
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

            // placeholder assertion when we expect an exception
            $this->assertTrue(true);
        }
    }

    public function provideConfigurationTests()
    {
        $tests = [];

        $tests[] = [
            [],
            ['http_client' => null, 'clients' => [], 'http_client_options' => []],
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
            ['http_client' => null, 'clients' => ['facebook1' => $fbConfig]],
            ['http_client' => null, 'clients' => ['facebook1' => $fbConfig], 'http_client_options' => []],
        ];

        $tests[] = [
            ['clients' => ['facebook2' => 'some_string']],
            false,
        ];

        return $tests;
    }

    public function testClientMergingConfig()
    {
        $fbConfig = [
            'type' => 'facebook',
            'client_id' => 'ABC',
            'client_secret' => '123',
            'graph_api_version' => '2.3',
            'redirect_route' => 'my_route',
            'redirect_params' => ['foo' => 'bars'],
        ];

        $processor = new Processor();

        $config = $processor->processConfiguration(
            new Configuration(), [
                ['clients' => ['fb1' => $fbConfig]],
                ['clients' => ['fb2' => $fbConfig]],
            ]
        );

        // verify the client keys are merged nicely
        $this->assertSame(
            ['fb1', 'fb2'],
            array_keys($config['clients'])
        );
    }
}
