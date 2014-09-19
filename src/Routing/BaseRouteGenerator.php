<?php

namespace Fortune\Routing;

use Fortune\Configuration\Configuration;

/**
 * Base class for implementations to generate routes.
 *
 * @package Fortune
 */
abstract class BaseRouteGenerator
{
    /**
     * Generates all the routes for resources.
     *
     * @return void
     */
    abstract public function generateRoutes();

    /**
     * The entire Configuration object
     *
     * @var Configuration
     */
    protected $configuration;

    /**
     * Constructor
     *
     * @param Configuration $configuration
     * @return void
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
}
