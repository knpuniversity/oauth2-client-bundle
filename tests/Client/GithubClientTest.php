<?php

namespace KnpU\OAuth2ClientBundle\Tests\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Token\AccessToken;

class GithubClientTest extends OAuth2ClientTest
{
    public function testFetchUser()
    {
        $this->request->request->set('code', 'CODE_ABC');

        $expectedToken = $this->prophesize(AccessToken::class);
        $this->provider->getAccessToken('authorization_code', ['code' => 'CODE_ABC'])
            ->willReturn($expectedToken->reveal());

        $this->provider->getHttpClient()->willReturn(new Client([
            'handler' => new MockHandler([
                new Response(200, [], \json_encode([
                    [
                        'email' => 'john@doe.com',
                        'primary' => true,
                    ],
                    [
                        'email' => 'contact@doe.com',
                        'primary' => false,
                    ]
                ]))
            ])
        ]));

        $client = new GithubClient(
            $this->provider->reveal(),
            $this->requestStack
        );

        $client->setAsStateless();
        $actualToken = $client->getAccessToken();

        $resourceOwner = new GithubResourceOwner([
            'id' => '1',
            'name' => 'John Doe',
            'login' => 'jdoe',
        ]);

        $this->provider->getResourceOwner($actualToken)->willReturn($resourceOwner);
        $user = $client->fetchUser($actualToken);

        $this->assertInstanceOf(GithubResourceOwner::class, $user);
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('john@doe.com', $user->getEmail());
    }

}