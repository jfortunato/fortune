<?php

namespace Fortune\Security\Bouncer;

use Fortune\Security\ResourceInspectorInterface;

abstract class Bouncer
{
    abstract public function check($entityOrClass);

    protected $inspector;

    public function __construct(ResourceInspectorInterface $inspector)
    {
        $this->inspector = $inspector;
    }

    protected function getEntityClass($entityOrClass)
    {
        return is_object($entityOrClass) ? get_class($entityOrClass):$entityOrClass;
    }
}
