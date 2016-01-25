<?php

namespace KnpU\OAuth2ClientBundle\Client;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ClientRegistry
{
    private $container;

    private $serviceMap;

    public function __construct(ContainerInterface $container, array $serviceMap)
    {
        $this->container = $container;
        $this->serviceMap = $serviceMap;
    }

    /**
     * Easy accessor for client objects.
     *
     * @param string $key
     * @return OAuth2Client
     */
    public function getClient($key)
    {
        if (!isset($this->serviceMap[$key])) {
            throw new \InvalidArgumentException(sprintf(
                'There is no OAuth2 provider "%s". Available are: %s',
                $key,
                implode(', ', array_keys($this->serviceMap))
            ));
        }

        return $this->container->get($this->serviceMap[$key]);
    }
}
