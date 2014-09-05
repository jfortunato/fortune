<?php

namespace Fortune\Serializer;

use JMS\Serializer\Serializer;
use Fortune\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class JMSSerializer implements SerializerInterface
{
    protected $serializer;

    protected $context;

    protected $excluder;

    public function __construct(Serializer $serializer, SerializationContext $context, JMSPropertyExcluder $excluder)
    {
        $this->serializer = $serializer;
        $this->context = $context;

        $this->context->addExclusionStrategy($excluder);
    }

    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json', $this->context);
    }
}
