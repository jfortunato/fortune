<?php

namespace Fortune\Resource\Creator;

use Fortune\Resource\Resource;
use Fortune\Configuration\Configuration;
use Fortune\Security\SecurityInterface;

abstract class ResourceCreator
{
    abstract protected function createResourceRepository($entityClass);

    protected $security;

    protected $config;

    public function __construct(Configuration $config, SecurityInterface $security)
    {
        $this->security = $security;
        $this->config = $config;
    }

    public function create($resourceName)
    {
        $resourceConfig = $this->config->resourceConfigurationFor($resourceName);

        $validatorClass = $resourceConfig->getValidatorClass();

        return new Resource(
            $this->createResourceRepository($resourceConfig->getEntityClass()),
            new $validatorClass,
            $this->security,
            $this->config,
            $this
        );
    }
}
