<?php

namespace Fortune\Security\Bouncer;

abstract class RoleBouncer extends Bouncer
{
    abstract public function hasRole($role);

    public function check($entityOrClass)
    {
        $role = $this->config->requiresRole($this->getEntityClass($entityOrClass));

        return $role ? $this->hasRole($role):true;
    }
}
