<?php

namespace Fortune\Routing;

use Fortune\Configuration\Configuration;

abstract class BaseRouteGenerator
{
    abstract public function generateRoutes();

    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
}
