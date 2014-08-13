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
}
