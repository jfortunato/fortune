<?php

namespace Fortune\Output;

class SimpleOutput implements OutputInterface
{
    public function response($content, $code)
    {
        http_response_code($code);

        return $content;
    }
}
