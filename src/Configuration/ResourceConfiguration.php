<?php

namespace Fortune\Configuration;

/**
 * Holds the user provided configuration for a single resource.
 *
 * @package Fortune
 */
class ResourceConfiguration
{
    /**
     * The identifier of the resource.
     *
     * @var string
     */
    protected $resourceName;

    /**
     * The configuration of a resource.
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor
     *
     * @param string $resourceName
     * @param array $config
     * @return void
     */
    public function __construct($resourceName, array $config)
    {
        $this->resourceName = $resourceName;
        $this->config = $config;
    }

    /**
     * Gets the value of resourceName
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resourceName;
    }

    /**
     * Gets the entity class from config.
     *
     * @return string|null
     */
    public function getEntityClass()
    {
        return $this->config['entity'];
    }

    /**
     * Sets the entity class in config.
     *
     * @param $entityClass
     *
     * @return ResourceConfiguration
     */
    public function setEntityClass($entityClass)
    {
        $this->config['entity'] = $entityClass;
        return $this;
    }

    /**
     * Gets the validator class from config.
     *
     * @return string|null
     */
    public function getValidatorClass()
    {
        return $this->config['validation'];
    }

    /**
     * Gets the parent resource if available.
     *
     * @return string|null
     */
    public function getParent()
    {
        return $this->config['parent'];
    }

    /**
     * Sets the parent resource in config.
     *
     * @param $parent
     *
     * @return ResourceConfiguration
     */
    public function setParent($parent)
    {
        $this->config['parent'] = $parent;
        return $this;
    }

    /**
     * Checks config to determine if auth is required for access to resource.
     *
     * @return boolean
     */
    public function requiresAuthentication()
    {
        return isset($this->config['access_control']['authentication']) ?
            $this->config['access_control']['authentication'] : false;
    }

    /**
     * Checks config to determine if a role is required for access to resource.
     *
     * @return string|null either a certain role that's required or null.
     */
    public function requiresRole()
    {
        return isset($this->config['access_control']['role']) ?
            $this->config['access_control']['role'] : null;
    }

    /**
     * Checks config to determine if owner is required for access to resource.
     *
     * @return boolean
     */
    public function requiresOwner()
    {
        return isset($this->config['access_control']['owner']) ?
            $this->config['access_control']['owner'] : false;
    }

    public function isUsingYamlValidation()
    {
        return is_array($this->config['validation']) ? true : false;
    }

    public function getYamlValidation()
    {
        return $this->config['validation'];
    }
}
