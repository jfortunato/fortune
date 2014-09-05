<?php

namespace Fortune\Output;

use Slim\Http\Request;
use Slim\Http\Response;
use Fortune\Output\BaseOutput;
use Fortune\Serializer\SerializerInterface;
use Fortune\ResourceInterface;

class SlimOutput extends BaseOutput
{
    protected $response;

    protected $request;

    public function __construct(
        Request $request,
        Response $response,
        SerializerInterface $serializer,
        ResourceInterface $resource
    ) {
        $this->request = $request;
        $this->response = $response;

        parent::__construct($serializer, $resource);
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

    protected function getInput()
    {
        parse_str($this->request->getBody(), $input);

        return $input;
    }
}
