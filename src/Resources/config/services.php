<?php

declare(strict_types=1);

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\DependencyInjection\ProviderFactory;
use KnpU\OAuth2ClientBundle\Security\User\OAuthUserProvider;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('knpu.oauth2.provider_factory', ProviderFactory::class)
        ->args([
            service('router'),
        ])

        ->set('knpu.oauth2.registry', ClientRegistry::class)
            ->public()
            ->args([
                service('service_container'),
                abstract_arg('serviceMap'),
            ])

        ->set('knpu.oauth2.user_provider', OAuthUserProvider::class)

        ->alias(ClientRegistry::class, 'knpu.oauth2.registry')

        ->alias('oauth2.registry', 'knpu.oauth2.registry')
            ->public();
};
