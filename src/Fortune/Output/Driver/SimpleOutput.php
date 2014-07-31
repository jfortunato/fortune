<?php

namespace Fortune\Output\Driver;

use Fortune\Output\OutputInterface;

class SimpleOutput implements OutputInterface
{
    public function response($content, $code)
    {
        http_response_code($code);

        return $content;
    }
}
