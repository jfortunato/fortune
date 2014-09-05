<?php

namespace Fortune\Security\Bouncer;

use Fortune\Security\Bouncer\AuthenticationBouncer;

/**
 * Implementation of AuthenticationBouncer
 *
 * @package Fortune
 */
class SimpleAuthenticationBouncer extends AuthenticationBouncer
{
    /**
     * @Override
     */
    public function isAuthenticated()
    {
        return isset($_SESSION['username']);
    }
}
