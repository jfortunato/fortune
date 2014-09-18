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
    public function __construct($resourceName, array $config = null)
    {
        $this->resourceName = $resourceName;
        $this->initializeConfig($config);
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

    public function getExcludedProperties()
    {
        return $this->config['exclude'];
    }

    /**
     * Checks config to determine if auth is required for access to resource.
     *
     * @return boolean
     */
    public function requiresAuthentication()
    {
        return $this->config['access_control']['authentication'];
    }

    /**
     * Checks config to determine if a role is required for access to resource.
     *
     * @return string|null either a certain role that's required or null.
     */
    public function requiresRole()
    {
        return $this->config['access_control']['role'];
    }

    public function isUsingYamlValidation()
    {
        return is_array($this->config['validation']) ? true : false;
    }

    public function getYamlValidation()
    {
        return $this->config['validation'];
    }

    protected function defaultConfigurations()
    {
        return array(
            'parent'         => null,
            'entity'         => null,
            'validation'     => array(),
            'access_control' => array(
                'authentication' => false,
                'role'           => null,
            ),
            'exclude'        => array(),
        );
    }

    protected function initializeConfig(array $config = null)
    {
        $default = $this->defaultConfigurations();

        $this->config = $config ? array_replace_recursive($default, $config) : $default;
    }
}
