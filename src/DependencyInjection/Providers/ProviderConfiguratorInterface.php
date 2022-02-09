<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

interface ProviderConfiguratorInterface
{
    /**
     * Build the config tree for any *extra* options that you need
     * to configure your provider.
     */
    public function buildConfiguration(NodeBuilder $node);

    /**
     * @return string
     */
    public function getProviderClass(array $configuration);

    /**
     * The client class to be used.
     *
     * Each provider should have their own, but you could
     * default to OAuth2Client.
     *
     * @return string
     */
    public function getClientClass(array $config);

    /**
     * @return array
     */
    public function getProviderOptions(array $configuration);

    /**
     * @return string
     */
    public function getPackagistName();

    /**
     * Returns the URL to the homepage for this library.
     *
     * @return string
     */
    public function getLibraryHomepage();

    /**
     * Display name like "Facebook" or "GitHub" that this integrates with.
     *
     * @return string
     */
    public function getProviderDisplayName();
}
