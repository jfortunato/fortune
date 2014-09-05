<?php

namespace Fortune\Configuration;

/**
 * Configuration
 *
 * @package Fortune
 */
class Configuration
{
    /**
     * A collection of ResourceConfiguration objects.
     *
     * @var array
     */
    protected $resourceConfigurations = array();

    /**
     * Constructor
     *
     * @param array $resources The raw resource configuration array for all resources.
     * @return void
     */
    public function __construct(array $resources = null)
    {
        if ($resources) {
            foreach ($resources as $resource => $config) {
                $this->addResourceConfiguration(new ResourceConfiguration($resource, $config));
            }
        }
    }

    /**
     * Add to our collection.
     *
     * @param ResourceConfiguration $resourceConfiguration
     * @return void
     */
    public function addResourceConfiguration(ResourceConfiguration $resourceConfiguration)
    {
        $this->resourceConfigurations[] = $resourceConfiguration;
    }

    /**
     * Given a request (such as $_SERVER['REQUEST_URI']) find the corresponding ResourceConfiguration object
     *
     * @param mixed $route
     * @return ResourceConfiguration
     */
    public function getResourceConfigurationFromRequest($route)
    {
        // get just the base url without query string
        $route = parse_url($route, PHP_URL_PATH);

        // remove any slashes from begining/end
        $route = trim($route, " /");

        // get the base resource excluding any single identifiers
        $parts = explode('/', $route);
        $resource = is_numeric(end($parts)) ? prev($parts):end($parts);

        return $this->resourceConfigurationFor($resource);
    }

    /**
     * Get the correct ResourceConfiguration based on current route
     *
     * @return ResourceConfiguration
     */
    public function getCurrentResourceConfiguration()
    {
        return $this->getResourceConfigurationFromRequest($_SERVER['REQUEST_URI']);
    }

    /**
     * Get the class of the current resource's entity.
     *
     * @return string
     */
    public function getCurrentEntityClass()
    {
        return $this->getCurrentResourceConfiguration()
            ->getEntityClass();
    }

    /**
     * Get the class of the current resource's validator.
     *
     * @return string
     */
    public function getCurrentValidatorClass()
    {
        return $this->getCurrentResourceConfiguration()
            ->getValidatorClass();
    }

    /**
     * Get ResourceConfiguration from resource name.
     *
     * @param mixed $resourceName
     * @return ResourceConfiguration|null
     */
    public function resourceConfigurationFor($resourceName)
    {
        foreach ($this->resourceConfigurations as $config) {
            if ($config->getResource() === $resourceName) {
                return $config;
            }
        }

        return null;
    }
}
