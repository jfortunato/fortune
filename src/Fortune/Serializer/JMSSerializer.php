<?php

namespace Fortune\Serializer;

use JMS\Serializer\Serializer;

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
