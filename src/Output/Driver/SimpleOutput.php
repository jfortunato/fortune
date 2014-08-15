<?php

namespace Fortune\Output\Driver;

use Fortune\Output\AbstractOutput;

class SimpleOutput extends AbstractOutput
{
    protected function setJsonHeader()
    {
        header('Content-Type: application/json');
    }

    protected function setStatusCode($code)
    {
        http_response_code($code);
    }

    protected function content($serializedContent)
    {
        return $serializedContent;
    }
}
