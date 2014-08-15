<?php

namespace Fortune\Output;

use Fortune\Serializer\SerializerInterface;

abstract class AbstractOutput
{
    abstract protected function setJsonHeader();
    abstract protected function setStatusCode($code);
    abstract protected function content($serializedContent);

    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function response($content, $code)
    {
        $this->setJsonHeader();

        $this->setStatusCode($code);

        return $this->content($this->serializer->serialize($content));
    }
}
