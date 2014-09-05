<?php

namespace Fortune\Output;

use Fortune\Output\BaseOutput;

class SimpleOutput extends BaseOutput
{
    /**
     * @Override
     */
    protected function setJsonHeader()
    {
        header('Content-Type: application/json');
    }

    /**
     * @Override
     */
    protected function setStatusCode($code)
    {
        http_response_code($code);
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
        return $_POST;
    }
}
