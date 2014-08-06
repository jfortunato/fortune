<?php

namespace Fortune\Output\Driver;

use Fortune\Output\OutputInterface;
use Slim\Http\Response;

class SlimOutput implements OutputInterface
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function response($content, $code)
    {
        $this->response->headers->set('Content-Type', 'application/json');

        $this->response->setStatus($code);

        return $content;
    }
}
