<?php

namespace Fortune\Security\Bouncer;

abstract class AuthenticationBouncer extends Bouncer
{
    abstract public function isAuthenticated();

    public function check($entityClass)
    {
        return $this->inspector->requiresAuthentication($entityClass) ? $this->isAuthenticated():true;
    }
}
