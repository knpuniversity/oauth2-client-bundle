<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\tests\Security\Exception;

use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class FinishRegistrationExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $userInfo = ['id' => '1', 'name' => 'testUser'];
        $e = new FinishRegistrationException($userInfo, '', 0);

        $this->assertEquals($e->getUserInformation(), $userInfo);
        $this->assertInternalType('string', $e->getMessageKey());
    }
}
