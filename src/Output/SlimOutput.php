<?php

namespace Fortune\Output;

use Slim\Http\Request;
use Slim\Http\Response;
use Fortune\Output\BaseOutput;
use Fortune\Serializer\SerializerInterface;
use Fortune\ResourceInterface;

class SlimOutput extends BaseOutput
{
    /**
     * The slim Request object
     *
     * @var Request
     */
    protected $request;

    /**
     * The slim Response object
     *
     * @var Response
     */
    protected $response;

    /**
     * Constructor
     *
     * @param Request $request
     * @param Response $response
     * @param SerializerInterface $serializer
     * @param ResourceInterface $resource
     * @return void
     */
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

    /**
     * @Override
     */
    protected function setJsonHeader()
    {
        $this->response->headers->set('Content-Type', 'application/json');
    }

    /**
     * @Override
     */
    protected function setStatusCode($code)
    {
        $this->response->setStatus($code);
    }

    /**
     * @Override
     */
    protected function content($serializedContent)
    {
        return $serializedContent;
    }

    /**
     * @Override
     */
    protected function getInput()
    {
        parse_str($this->request->getBody(), $input);

        return $input;
    }
}
