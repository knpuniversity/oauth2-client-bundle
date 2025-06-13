<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Client;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\DependencyInjection\InvalidOAuth2ClientException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClientRegistryTest extends TestCase
{
    /**
     * @group legacy
     * @legacy drop legacy group when no longer using guard authenticators
     */
    public function testShouldKnowWhatServicesAreConfigured()
    {
        $mockServiceMap = [
            'facebook' => 'knpu.oauth2.client.facebook',
            'google' => 'knpu.oauth2.client.google',
            'okta' => 'knpu.oauth2.client.okta'
        ];
        $mockContainer = $this->getMockBuilder(ContainerInterface::class)->disableOriginalConstructor()->getMock();

        $testClientRegistry = new ClientRegistry($mockContainer, $mockServiceMap);

        $results = $testClientRegistry->getEnabledClientKeys();
        $this->assertEquals([
            "facebook",
            "google",
            "okta"
        ], $results);
    }

    public function testShouldThrowExceptionIfClientDoesNotExist()
    {
        $mockServiceMap = [
            'facebook' => 'knpu.oauth2.client.facebook',
            'google' => 'knpu.oauth2.client.google',
            'okta' => 'knpu.oauth2.client.okta'
        ];
        $mockContainer = $this->getMockBuilder(ContainerInterface::class)->disableOriginalConstructor()->getMock();

        $testClientRegistry = new ClientRegistry($mockContainer, $mockServiceMap);

        $this->expectException(InvalidOAuth2ClientException::class);

        $testClientRegistry->getClient("unknownClient");
    }

    public function testShouldThrowExceptionIfClientExistsButNotOAuth2Client()
    {
        $mockServiceMap = [
            'facebook' => 'knpu.oauth2.client.facebook',
            'google' => 'knpu.oauth2.client.google',
            'okta' => 'knpu.oauth2.client.okta',
            'invalid' => 'knpu.oauth2.client.invalid'
        ];
        $mockContainer = $this->getMockBuilder(ContainerInterface::class)->disableOriginalConstructor()->getMock();
        $mockContainer->method("get")->willReturn(new \stdClass());

        $testClientRegistry = new ClientRegistry($mockContainer, $mockServiceMap);

        $this->expectException(InvalidOAuth2ClientException::class);

        $testClientRegistry->getClient("invalid");
    }

    public function testShouldReturnValidClient()
    {
        $mockServiceMap = [
            'facebook' => 'knpu.oauth2.client.facebook',
            'google' => 'knpu.oauth2.client.google',
            'okta' => 'knpu.oauth2.client.okta'
        ];
        $mockClient = $this->getMockBuilder(OAuth2ClientInterface::class)->getMock();
        $mockContainer = $this->getMockBuilder(ContainerInterface::class)->disableOriginalConstructor()->getMock();
        $mockContainer->method("get")->willReturn($mockClient);

        $testClientRegistry = new ClientRegistry($mockContainer, $mockServiceMap);

        $result = $testClientRegistry->getClient("facebook");

        $this->assertInstanceOf(OAuth2ClientInterface::class, $result);
    }
}
