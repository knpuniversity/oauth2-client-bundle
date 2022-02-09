<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Security\Exception;

use Exception;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Used to help direct a user to "finish registration" during Guard authentication.
 */
class FinishRegistrationException extends AuthenticationException
{
    private $userInformation;

    /**
     * @param mixed      $userInfo Any info to be used to help registration
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($userInfo, $message = '', $code = 0, Exception $previous = null)
    {
        $this->userInformation = $userInfo;

        parent::__construct($message, $code, $previous);
    }

    public function getUserInformation()
    {
        return $this->userInformation;
    }

    /**
     * This method should *not* be called normally. The purpose
     * of this exception is to be a "flag" for code that will
     * redirect the user to some "finish registration" page. If
     * this message is shown to your user, then you're missing
     * this piece of your code.
     */
    public function getMessageKey(): string
    {
        return 'You need to finish registration to login.';
    }
}
