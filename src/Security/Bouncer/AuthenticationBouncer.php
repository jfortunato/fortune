<?php

namespace Fortune\Security\Bouncer;

abstract class AuthenticationBouncer extends Bouncer
{
    abstract public function isAuthenticated();

    public function check($entityOrClass)
    {
        return $this->inspector->requiresAuthentication($this->getEntityClass($entityOrClass)) ? $this->isAuthenticated():true;
    }
}
