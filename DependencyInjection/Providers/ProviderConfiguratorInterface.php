<?php

namespace KnpU\OAuth2ClientBundle\DependencyInjection\Providers;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

interface ProviderConfiguratorInterface
{
    /**
     * Build the config tree for any *extra* options that you need
     * to configure your provider.
     *
     * @param NodeBuilder $node
     */
    public function buildConfiguration(NodeBuilder $node);

    /**
     * @return string
     */
    public function getProviderClass();

    /**
     * @param array $configuration
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
