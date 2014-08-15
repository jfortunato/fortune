<?php

namespace Fortune\Output\Driver;

use Slim\Http\Response;
use Fortune\Output\AbstractOutput;
use Fortune\Serializer\SerializerInterface;

class SlimOutput extends AbstractOutput
{
    protected $response;

    public function __construct(Response $response, SerializerInterface $serializer)
    {
        $this->response = $response;

        parent::__construct($serializer);
    }

    protected function setJsonHeader()
    {
        $this->response->headers->set('Content-Type', 'application/json');
    }

    protected function setStatusCode($code)
    {
        $this->response->setStatus($code);
    }

    protected function content($serializedContent)
    {
        return $serializedContent;
    }
}
