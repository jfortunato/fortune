<?php

namespace Fortune\Security\Bouncer;

use Fortune\Security\Bouncer\RoleBouncer;

/**
 * Implementation of RoleBouncer
 *
 * @package Fortune
 */
class SimpleRoleBouncer extends RoleBouncer
{
    /**
     * @Override
     */
    public function hasRole($role)
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }
}
