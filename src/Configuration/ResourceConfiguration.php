<?php

namespace Fortune\Configuration;

class ResourceConfiguration
{
    protected $resource = 'dogs';

    protected $entityClass = 'Fortune\Test\Entity\Dog';

    protected $validatorClass = 'Fortune\Test\Validator\DogValidator';

    protected $parent;

    public function __construct($resource = null, $entityClass = null, $validatorClass = null, $parent = null)
    {
        $this->resource = $resource;
        $this->entityClass = $entityClass;
        $this->validatorClass = $validatorClass;
        $this->parent = $parent;
    }

    /**
     * Gets the value of resource
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Gets the value of entityClass
     *
     * @return string|null
     */
    public function getEntityClass()
    {
        return $this->entityClass;
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
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * Gets the value of validatorClass
     *
     * @return string|null
     */
    public function getValidatorClass()
    {
        return $this->validatorClass;
    }

    /**
     * Gets the value of parent
     *
     * @return string|null
     */
    public function getParent()
    {
        return $this->parent;
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
        $this->parent = $parent;
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
}
