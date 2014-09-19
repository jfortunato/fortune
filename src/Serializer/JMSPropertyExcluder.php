<?php

namespace Fortune\Serializer;

use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Context;
use JMS\Serializer\Metadata\PropertyMetadata;

/**
 * Takes an input of properties that should be excluded from serialized output.
 *
 * @package Fortune
 */
class JMSPropertyExcluder implements ExclusionStrategyInterface
{
    /**
     * All the properties that should be excluded for a resource.
     *
     * @var array
     */
    protected $excludedProperties;

    /**
     * Constructor
     *
     * @param array $excludedProperties
     * @return void
     */
    public function __construct(array $excludedProperties = array())
    {
        $this->excludedProperties = $excludedProperties;
    }

    /**
     * @Override
     */
    public function shouldSkipClass(ClassMetadata $metadata, Context $context)
    {
    }

    /**
     * @Override
     */
    public function shouldSkipProperty(PropertyMetadata $property, Context $context)
    {
        return in_array($property->name, $this->excludedProperties);
    }
}
