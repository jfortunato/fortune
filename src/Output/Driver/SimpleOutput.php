<?php

namespace Fortune\Output\Driver;

use Fortune\Output\BaseOutput;

class SimpleOutput extends BaseOutput
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
