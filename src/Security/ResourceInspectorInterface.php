<?php

namespace Fortune\Security;

interface ResourceInspectorInterface
{
    public function requiresAuthentication($entityClass);
}
