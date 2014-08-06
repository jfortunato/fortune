<?php

namespace Fortune\Security\Bouncer;

use Fortune\Security\ResourceInspectorInterface;

abstract class Bouncer
{
    abstract public function check($entityClass);

    protected $inspector;

    public function __construct(ResourceInspectorInterface $inspector)
    {
        $this->inspector = $inspector;
    }
}
