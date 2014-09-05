<?php

namespace Fortune\Security\Bouncer;

use Fortune\Configuration\ResourceConfiguration;

abstract class Bouncer
{
    abstract public function check($entityOrClass);

    protected $config;

    public function __construct(ResourceConfiguration $config)
    {
        $this->config = $config;
    }

    protected function getEntityClass($entityOrClass)
    {
        return is_object($entityOrClass) ? get_class($entityOrClass):$entityOrClass;
    }
}
