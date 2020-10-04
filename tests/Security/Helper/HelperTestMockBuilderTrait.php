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

trait HelperTestMockBuilderTrait
{
    private function getMockRequest($hasSessionReturn, $getSessionReturn = \stdClass::class)
    {
        $mockRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockRequest->method("hasSession")->willReturn($hasSessionReturn);
        $mockRequest->method("getSession")->willReturn($getSessionReturn);
        return $mockRequest;
    }
}