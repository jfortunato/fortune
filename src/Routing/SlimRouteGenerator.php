<?php

namespace Fortune\Routing;

use Fortune\Configuration\Configuration;
use Slim\Slim;
use Fortune\Output\SlimOutput;
use Fortune\Configuration\ResourceConfiguration;

class SlimRouteGenerator extends BaseRouteGenerator
{
    protected $slim;

    protected $output;

    public function __construct(Slim $slim, SlimOutput $output, Configuration $configuration)
    {
        $this->slim = $slim;
        $this->output = $output;

        parent::__construct($configuration);
    }

    public function generateRoutes()
    {
        foreach ($this->configuration->getResourceConfigurations() as $resourceConfig) {
            $this->generateGetAll($resourceConfig);
            $this->generateGetSingle($resourceConfig);
            $this->generatePost($resourceConfig);
            $this->generatePut($resourceConfig);
            $this->generateDelete($resourceConfig);
        }
    }

    protected function generateGetAll(ResourceConfiguration $config)
    {
        $this->slim->get($this->baseRoute($config), function ($parentId = null) {
            $this->executeRoute('index', func_get_args());
        });
    }

    protected function generateGetSingle(ResourceConfiguration $config)
    {
        $this->slim->get("{$this->baseRoute($config)}/:id", function ($id, $parentId = null) {
            $this->executeRoute('show', func_get_args());
        });
    }

    protected function generatePost(ResourceConfiguration $config)
    {
        $this->slim->post($this->baseRoute($config), function ($parentId = null) {
            $this->executeRoute('create', func_get_args());
        });
    }

    protected function generatePut(ResourceConfiguration $config)
    {
        $this->slim->put("{$this->baseRoute($config)}/:id", function ($id, $parentId = null) {
            $this->executeRoute('update', func_get_args());
        });
    }

    protected function generateDelete(ResourceConfiguration $config)
    {
        $this->slim->delete("{$this->baseRoute($config)}/:id", function ($id, $parentId = null) {
            $this->executeRoute('delete', func_get_args());
        });
    }

    protected function baseRoute(ResourceConfiguration $config)
    {
        $parent = $config->getParent() ?
            "/{$config->getParent()}/:{$config->getParentEntityProperty()}" : "";

        return "{$parent}/{$config->getResource()}";
    }

    protected function executeRoute($method, array $routeParams)
    {
        echo call_user_func_array(array($this->output, $method), array_reverse($routeParams));
    }
}
