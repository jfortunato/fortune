<?php

namespace Fortune\Serializer\Driver;

use JMS\Serializer\Serializer;
use Fortune\Serializer\SerializerInterface;

class JMSSerializer implements SerializerInterface
{
    protected $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }


    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json');
    }
}
