<?php

namespace Fortune\Security\Bouncer;

use Fortune\Configuration\ResourceConfiguration;

/**
 * A base class for all bouncers.
 *
 * @package Fortune
 */
abstract class Bouncer
{
    /**
     * Derived classes determine how they should judge if a
     * resource should be accessible.
     *
     * @return boolean
     */
    abstract public function check($method, $resource, $identifiers = null);

    /**
     * A ResourceConfiguration for a single resource.
     *
     * @var ResourceConfiguration
     */
    protected $config;

    /**
     * Constructor
     *
     * @param ResourceConfiguration $config
     * @return void
     */
    public function __construct(ResourceConfiguration $config)
    {
        $this->config = $config;
    }
}
