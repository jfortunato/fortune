<?php

namespace Fortune\Security;

use Fortune\Security\ResourceInspectorInterface;

abstract class AuthenticationBouncer implements SecurityInterface
{
    abstract public function isAuthenticated();

    protected $inspector;

    public function __construct(ResourceInspectorInterface $inspector)
    {
        $this->inspector = $inspector;
    }


    public function isAllowed($entityClass)
    {
        return $this->inspector->requiresAuthentication($entityClass) ? $this->isAuthenticated():true;
    }
}
