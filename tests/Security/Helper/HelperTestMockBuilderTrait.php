<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

trait HelperTestMockBuilderTrait
{
    private function createRequest(bool $withSession = true): Request
    {
        $request = Request::create('/');
        if ($withSession) {
            $request->setSession(new Session(new MockArraySessionStorage()));
        }

        return $request;
    }
}
