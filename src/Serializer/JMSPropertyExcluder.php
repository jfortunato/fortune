<?php

namespace Fortune\Serializer;

use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Context;
use JMS\Serializer\Metadata\PropertyMetadata;

class JMSPropertyExcluder implements ExclusionStrategyInterface
{
    protected $excludedProperties;

    public function __construct(array $excludedProperties = array())
    {
        $this->excludedProperties = $excludedProperties;
    }

    public function shouldSkipClass(ClassMetadata $metadata, Context $context)
    {
    }

    public function shouldSkipProperty(PropertyMetadata $property, Context $context)
    {
        return in_array($property->name, $this->excludedProperties);
    }
}
