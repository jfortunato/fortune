<?php

namespace Fortune\Configuration;

class Configuration
{
    protected $resourceConfigurations = array();

    public function __construct(array $resources = null)
    {
        if ($resources) {
            foreach ($resources as $resource => $config) {
                $this->addResourceConfiguration(new ResourceConfiguration($resource, $config));
            }
        }
    }

    public function addResourceConfiguration(ResourceConfiguration $resourceConfiguration)
    {
        $this->resourceConfigurations[] = $resourceConfiguration;
    }

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

    public function getCurrentResourceConfiguration()
    {
        return $this->getResourceConfigurationFromRequest($_SERVER['REQUEST_URI']);
    }

    public function getCurrentEntityClass()
    {
        return $this->getResourceConfigurationFromRequest($_SERVER['REQUEST_URI'])
            ->getEntityClass();
    }

    public function getCurrentValidatorClass()
    {
        return $this->getResourceConfigurationFromRequest($_SERVER['REQUEST_URI'])
            ->getValidatorClass();
    }

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
