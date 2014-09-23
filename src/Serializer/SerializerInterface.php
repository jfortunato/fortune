<?php

namespace Fortune\Serializer;

interface SerializerInterface
{
    /**
     * Takes an input and outputs a serialized version of it.
     *
     * @param mixed $data
     * @return string The serialized data.
     */
    public function serialize(array $data = null);
}
