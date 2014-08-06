<?php

namespace Fortune\Security;

class ResourceInspector implements ResourceInspectorInterface
{
    public function requiresAuthentication($entityClass)
    {
        $reflectionClass = new \ReflectionClass($entityClass);

        return $reflectionClass->getStaticPropertyValue('requiresAuthentication', false);
    }

    public function requiredRole($entityClass)
    {
        $reflectionClass = new \ReflectionClass($entityClass);

        return $reflectionClass->getStaticPropertyValue('requiresRole', null);
    }
}
