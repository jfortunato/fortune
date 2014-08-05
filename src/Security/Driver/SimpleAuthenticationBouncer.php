<?php

namespace Fortune\Security\Driver;

use Fortune\Security\AuthenticationBouncer;

class SimpleAuthenticationBouncer extends AuthenticationBouncer
{
    public function isAuthenticated()
    {
        return isset($_SESSION['username']);
    }
}
