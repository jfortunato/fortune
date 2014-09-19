<?php

namespace Fortune\Routing;

use Fortune\Configuration\Configuration;
use Slim\Slim;
use Fortune\Output\SlimOutput;
use Fortune\Configuration\ResourceConfiguration;

/**
 * Slim implementation for generating all resource routes.
 *
 * @package Fortune
 */
class SlimRouteGenerator extends BaseRouteGenerator
{
    /**
     * The Slim app.
     *
     * @var Slim
     */
    protected $slim;

    /**
     * Slim implementation for calling the routes.
     *
     * @var SlimOutput
     */
    protected $output;

    /**
     * Constructor
     *
     * @param Slim $slim
     * @param SlimOutput $output
     * @param Configuration $configuration
     * @return void
     */
    public function __construct(Slim $slim, SlimOutput $output, Configuration $configuration)
    {
        $this->slim = $slim;
        $this->output = $output;

        parent::__construct($configuration);
    }

    /**
     * @Override
     */
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

    /**
     * Generates the index route.
     *
     * @param ResourceConfiguration $config
     * @return void
     */
    protected function generateGetAll(ResourceConfiguration $config)
    {
        $that = $this;

        $this->slim->get($this->baseRoute($config), function () use ($that) {
            $that->executeRoute('index', func_get_args());
        });
    }

    /**
     * Generates the show route.
     *
     * @param ResourceConfiguration $config
     * @return void
     */
    protected function generateGetSingle(ResourceConfiguration $config)
    {
        $that = $this;

        $this->slim->get("{$this->baseRoute($config)}/:id", function () use ($that) {
            $that->executeRoute('show', func_get_args());
        });
    }

    /**
     * Generates the create route.
     *
     * @param ResourceConfiguration $config
     * @return void
     */
    protected function generatePost(ResourceConfiguration $config)
    {
        $that = $this;

        $this->slim->post($this->baseRoute($config), function () use ($that) {
            $that->executeRoute('create', func_get_args());
        });
    }

    /**
     * Generates the update route.
     *
     * @param ResourceConfiguration $config
     * @return void
     */
    protected function generatePut(ResourceConfiguration $config)
    {
        $that = $this;

        $this->slim->put("{$this->baseRoute($config)}/:id", function () use ($that) {
            $that->executeRoute('update', func_get_args());
        });
    }

    /**
     * Generates the delete route.
     *
     * @param ResourceConfiguration $config
     * @return void
     */
    protected function generateDelete(ResourceConfiguration $config)
    {
        $that = $this;

        $this->slim->delete("{$this->baseRoute($config)}/:id", function () use ($that) {
            $that->executeRoute('delete', func_get_args());
        });
    }

    /**
     * Gets the base route for a resource including parents, excluding identifier.
     *
     * @param ResourceConfiguration $config
     * @return string
     */
    protected function baseRoute(ResourceConfiguration $config)
    {
        $parent = $this->parentRoutesFor($config);

        return "{$parent}/{$config->getResource()}";
    }

    /**
     * Gets the parents routes for a given child resource.
     *
     * @param ResourceConfiguration $config
     * @return string
     */
    protected function parentRoutesFor(ResourceConfiguration $config)
    {
        if ($config->getParent()) {
            $parentConfig = $this->configuration->resourceConfigurationFor($config->getParent());

            return $this->parentRoutesFor($parentConfig) . "/{$config->getParent()}/:{$config->getParent()}_id";
        }
    }

    /**
     * Calls the $method on the SlimOutput object
     *
     * @param string $method
     * @param array $routeParams
     * @return void
     */
    public function executeRoute($method, array $routeParams)
    {
        echo call_user_func_array(array($this->output, $method), $routeParams);
    }
}
