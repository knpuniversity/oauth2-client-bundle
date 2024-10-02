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
    private UrlGeneratorInterface $generator;

    /**
     * ProviderFactory constructor.
     */
    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Creates a provider of the given class.
     *
     * @param string $class
     */
    public function createProvider($class, array $options, ?string $redirectUri = null, array $redirectParams = [], array $collaborators = [])
    {
        if (null !== $redirectUri) {
            $redirectUri = filter_var($redirectUri, \FILTER_VALIDATE_URL)
                ? $redirectUri
                : $this->generator->generate($redirectUri, $redirectParams, UrlGeneratorInterface::ABSOLUTE_URL);

            $options['redirectUri'] = $redirectUri;
        }

        return new $class($options, $collaborators);
    }
}
