<?php

namespace Fortune\Security;

interface ResourceInspectorInterface
{
    public function requiresAuthentication($entityClass);
    public function requiredRole($entityClass);
}
