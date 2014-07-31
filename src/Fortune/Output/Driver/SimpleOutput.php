<?php

namespace Fortune\Output\Driver;

use Fortune\Output\OutputInterface;

class SimpleOutput implements OutputInterface
{
    public function response($content, $code)
    {
        header('Content-Type: application/json');
        http_response_code($code);

        return $content;
    }
}
