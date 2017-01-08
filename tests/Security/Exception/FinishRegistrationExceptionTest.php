<?php
/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 * Date: 07/01/2017
 * Time: 23:21
 */

namespace KnpU\OAuth2ClientBundle\Tests\Security\Exception;

use KnpU\OAuth2ClientBundle\Security\Exception\FinishRegistrationException;


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
