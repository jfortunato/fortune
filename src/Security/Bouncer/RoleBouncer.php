<?php

namespace Fortune\Security\Bouncer;

/**
 * Determines resource accessibility based user role. Subclass
 * determines implementaion.
 *
 * @package Fortune
 */
abstract class RoleBouncer extends Bouncer
{
    /**
     * Checks if user has a certain role.
     *
     * @param string $role
     * @return boolean
     */
    abstract public function hasRole($role);

    /**
     * @Override
     */
    public function check($method, $resource, $identifiers = null)
    {
        $role = $this->config->requiresRole();

        return $role ? $this->hasRole($role):true;
    }
}
