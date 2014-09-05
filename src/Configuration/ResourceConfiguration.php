<?php

namespace Fortune\Configuration;

class ResourceConfiguration
{
    protected $resourceName;

    protected $config;

    public function __construct($resourceName, array $config)
    {
        $this->resourceName = $resourceName;
        $this->config = $config;
    }

    /**
     * Gets the value of resource
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resourceName;
    }

    /**
     * Gets the value of entityClass
     *
     * @return string|null
     */
    public function getEntityClass()
    {
        return $this->config['entity'];
    }

    /**
     * Sets the value of EntityClass
     *
     * @param $entityClass description
     *
     * @return ResourceConfiguration
     */
    public function setEntityClass($entityClass)
    {
        $this->config['entity'] = $entityClass;
        return $this;
    }

    /**
     * Gets the value of validatorClass
     *
     * @return string|null
     */
    public function getValidatorClass()
    {
        return $this->config['validator'];
    }

    /**
     * Gets the value of parent
     *
     * @return string|null
     */
    public function getParent()
    {
        return $this->config['parent'];
    }

    /**
     * Sets the value of parent
     *
     * @param $parent description
     *
     * @return ResourceConfiguration
     */
    public function setParent($parent)
    {
        $this->config['parent'] = $parent;
        return $this;
    }

    public function getParentEntityProperty()
    {
        // the parent resource may be pluralized
        // but the property name is most likely singular
        // if property name actually plural just use that
        // otherwise make it singular
        $reflection = new \ReflectionClass($this->getEntityClass());

        return $reflection->hasProperty($this->getParent())
            ? $this->getParent() : preg_replace('/s$/', '', $this->getParent());
    }

    public function requiresAuthentication()
    {
        return isset($this->config['access_control']['authentication']) ?
            $this->config['access_control']['authentication'] : false;
    }

    public function requiresRole()
    {
        return isset($this->config['access_control']['role']) ?
            $this->config['access_control']['role'] : null;
    }

    public function requiresOwner()
    {
        return isset($this->config['access_control']['owner']) ?
            $this->config['access_control']['owner'] : false;
    }
}
