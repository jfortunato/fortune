<?php

namespace Fortune\Configuration;

class Configuration
{
    protected $resourceConfigurations = array();

    public function getResourceConfigurationFromRequest($route)
    {
        // get just the base url without query string
        $route = parse_url($route, PHP_URL_PATH);

        // remove any slashes from begining/end
        $route = trim($route, " /");

        // get the base resource excluding any single identifiers
        $parts = explode('/', $route);
        $resource = is_numeric(end($parts)) ? prev($parts):end($parts);

        foreach ($this->resourceConfigurations as $config) {
            if ($config->getResource() === $resource) {
                return $config;
            }
        }

        return null;
    }

    public function addResourceConfiguration(ResourceConfiguration $resourceConfiguration)
    {
        $this->resourceConfigurations[] = $resourceConfiguration;
    }
}
