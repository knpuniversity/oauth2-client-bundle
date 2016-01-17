<?php

namespace KnpU\OAuth2ClientBundle;

use KnpU\OAuth2ClientBundle\DependencyInjection\KnpUOAuth2ClientExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KnpUOAuth2ClientBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     *
     * @return KnpUOAuth2ClientExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            return new KnpUOAuth2ClientExtension();
        }

        return $this->extension;
    }
}
