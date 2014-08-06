<?php

namespace Fortune\Security\Bouncer\Driver;

use Fortune\Security\Bouncer\AuthenticationBouncer;

class SimpleAuthenticationBouncer extends AuthenticationBouncer
{
    public function isAuthenticated()
    {
        return isset($_SESSION['username']);
    }
}
