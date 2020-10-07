<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests;

use KnpU\OAuth2ClientBundle\DependencyInjection\KnpUOAuth2ClientExtension;
use KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle;
use PHPUnit\Framework\TestCase;

class KnpUOAuth2ClientBundleTest extends TestCase
{
    public function testShouldReturnNewContainerExtension()
    {
        $testBundle = new KnpUOAuth2ClientBundle();

        $result = $testBundle->getContainerExtension();
        $this->assertInstanceOf(KnpUOAuth2ClientExtension::class, $result);
    }
}
