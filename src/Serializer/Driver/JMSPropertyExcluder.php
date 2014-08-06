<?php

namespace Fortune\Serializer\Driver;

use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Context;
use JMS\Serializer\Metadata\PropertyMetadata;

class JMSPropertyExcluder implements ExclusionStrategyInterface
{
    protected $skip = array(
        'requiresAuthentication',
        'requiresRole',
        'requiresOwner',
    );

    public function shouldSkipClass(ClassMetadata $metadata, Context $context)
    {
    }

    public function shouldSkipProperty(PropertyMetadata $property, Context $context)
    {
        return in_array($property->name, $this->skip);
    }
}
