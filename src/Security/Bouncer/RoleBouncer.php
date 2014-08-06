<?php

namespace Fortune\Security\Bouncer;

abstract class RoleBouncer extends Bouncer
{
    abstract public function hasRole($role);

    public function check($entityOrClass)
    {
        $role = $this->inspector->requiredRole($this->getEntityClass($entityOrClass));

        return $role ? $this->hasRole($role):true;
    }
}
