<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Used to create the individual Provider objects in the service container.
 *
 * You won't need to use this directly.
 */
class ProviderFactory
{
    private $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function createProvider($class, array $options, $redirectUri, array $redirectParams = [])
    {
        $redirectUri = $this->generator
            ->generate($redirectUri, $redirectParams, UrlGeneratorInterface::ABSOLUTE_URL);

        $options['redirectUri'] = $redirectUri;
        // todo - make this configuration when someone needs this
        $collaborators = [];

        return new $class($options, $collaborators);
    }
}
