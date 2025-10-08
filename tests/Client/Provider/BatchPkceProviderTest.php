<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Client\Provider;

use KnpU\OAuth2ClientBundle\Client\OAuth2PKCEClient;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class BatchPkceProviderTest extends TestCase
{
    public function testProviders()
    {
        // This is basically just validating that the clients are sane/implementing OAuth2PkceClient

        $mockAccessToken = $this->getMockBuilder(AccessToken::class)->disableOriginalConstructor()->getMock();
        $mockProvider = $this->getMockProvider($mockAccessToken);
        $requestStack = $this->getRequestStack($this->getRequest());

        $clients = scandir(__DIR__ . "/../../../src/Client/Provider/Pkce");
        foreach($clients as $client) {
            if(!str_ends_with($client, ".php")) {
                continue;
            }

            $client = sprintf("KnpU\OAuth2ClientBundle\Client\Provider\Pkce\%s", explode(".", $client)[0]);
            $testClient = new $client($mockProvider, $requestStack);
            $testClient->setAsStateless();
            $this->assertTrue(is_subclass_of($testClient, OAuth2PKCEClient::class));

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

    private function getRequest(): Request
    {
        $request = new Request();

        $session = new Session(new MockArraySessionStorage());
        $session->set(OAuth2PKCEClient::VERIFIER_KEY, 'test_code_verifier');

        $request->setSession($session);
        $request->query->set('code', 'test_code_verifier');

        return $request;
    }

    private function getRequestStack(Request $request): RequestStack
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }
}
