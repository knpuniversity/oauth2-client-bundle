<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BatchProviderTest extends TestCase
{
    public function testProviders()
    {
        // This is basically just validating that the clients are sane/implementing OAuth2Client

        $mockAccessToken = $this->getMockBuilder(AccessToken::class)->disableOriginalConstructor()->getMock();
        $mockProvider = $this->getMockProvider($mockAccessToken);
        $mockRequestStack = $this->getMockRequestStack($this->getMockRequest());

        $clients = scandir(__DIR__ . "/../../../src/Client/Provider");
        foreach($clients as $client) {
            if(substr($client, -4, 4) !== ".php") { continue; }

            $client = sprintf("KnpU\OAuth2ClientBundle\Client\Provider\%s", explode(".", $client)[0]);
            $testClient = new $client($mockProvider, $mockRequestStack);
            $testClient->setAsStateless();
            $this->assertTrue(is_subclass_of($testClient, OAuth2Client::class));

            $this->assertInstanceOf(ResourceOwnerInterface::class, $testClient->fetchUserFromToken($mockAccessToken));
            $this->assertInstanceOf(ResourceOwnerInterface::class, $testClient->fetchUser());
        }
    }

    private function getMockProvider($mockAccessToken)
    {
        $mockProvider = $this->getMockBuilder(AbstractProvider::class)->getMock();
        $mockProvider->method("getResourceOwner")->willReturn($this->getMockBuilder(ResourceOwnerInterface::class)->getMock());
        $mockProvider->method("getAccessToken")->willReturn($mockAccessToken);
        return $mockProvider;
    }

    private function getMockRequest()
    {
        $mockRequest = $this->getMockBuilder(Request::class)->getMock();
        $mockRequest->method("get")->willReturn(true);
        return $mockRequest;
    }

    private function getMockRequestStack($mockRequest)
    {
        $mockRequestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $mockRequestStack->method("getCurrentRequest")->willReturn($mockRequest);
        return $mockRequestStack;
    }
}
