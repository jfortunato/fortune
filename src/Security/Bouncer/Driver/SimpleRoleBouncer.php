<?php

namespace Fortune\Security\Bouncer\Driver;

use Fortune\Security\Bouncer\RoleBouncer;

class SimpleRoleBouncer extends RoleBouncer
{
    public function hasRole($role)
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }
}