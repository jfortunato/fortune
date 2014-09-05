<?php

namespace Fortune\Serializer;

use JMS\Serializer\Serializer;
use Fortune\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

/**
 * Uses JMSSerializer to serialize data.
 *
 * @package Fortune
 */
class JMSSerializer implements SerializerInterface
{
    /**
     * The object that does most of the work.
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Needed for Serializer
     *
     * @var SerializationContext
     */
    protected $context;

    /**
     * Tells Serializer which properties to skip.
     *
     * @var JMSPropertyExcluder
     */
    protected $excluder;

    /**
     * Constructor
     *
     * @param Serializer $serializer
     * @param SerializationContext $context
     * @param JMSPropertyExcluder $excluder
     * @return void
     */
    public function __construct(Serializer $serializer, SerializationContext $context, JMSPropertyExcluder $excluder)
    {
        $this->serializer = $serializer;
        $this->context = $context;

        $this->context->addExclusionStrategy($excluder);
    }

    /**
     * @Override
     */
    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json', $this->context);
    }
}
