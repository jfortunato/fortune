<?php

namespace Fortune\Security\Bouncer;

use Fortune\Configuration\ResourceConfiguration;

class ParentBouncer extends Bouncer
{
    public function check($entityOrClass)
    {
        return $this->config->getParent() ? $this->routeContainsParent():true;
    }

    protected function routeContainsParent()
    {
        // get just the base url
        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // lets split it into pieces
        $parts = explode('/', $route);

        // if a resource has a parent, it should be in the form /dogs/1/puppies
        // so the parent resource should be 2 spots before the child resource
        $resourcePosition = array_search($this->config->getResource(), $parts);

        return isset($parts[$resourcePosition - 2]);
    }
}
