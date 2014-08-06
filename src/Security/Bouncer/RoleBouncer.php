<?php

namespace Fortune\Security\Bouncer;

abstract class RoleBouncer extends Bouncer
{
    abstract public function hasRole($role);

    public function check($entityClass)
    {
        $role = $this->inspector->requiredRole($entityClass);

        return $role ? $this->hasRole($role):true;
    }
}
