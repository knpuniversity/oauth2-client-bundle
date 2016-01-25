<?php

namespace KnpU\OAuth2ClientBundle\Tests;

use KnpU\OAuth2ClientBundle\DependencyInjection\Configuration;
use KnpU\OAuth2ClientBundle\Tests\app\TestKernel;
use Symfony\Component\Config\Definition\Processor;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function testServicesAreUsable()
    {
        $kernel = new TestKernel('dev', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->assertTrue($container->has('knpu.oauth2.client.my_facebook'));

        /** @var \KnpU\OAuth2ClientBundle\Client\OAuth2Client $client */
        $client = $container->get('knpu.oauth2.client.my_facebook');
        $this->assertInstanceOf('KnpU\OAuth2ClientBundle\Client\OAuth2Client', $client);
        $this->assertInstanceOf('League\OAuth2\Client\Provider\Facebook', $client->getOAuth2Provider());

        $client2 = $container->get('knpu.oauth2.registry')
            ->getClient('my_facebook');
        $this->assertSame($client, $client2);
        $this->assertTrue($container->has('oauth2.registry'));
    }
}
