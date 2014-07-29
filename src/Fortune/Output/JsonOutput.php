<?php

namespace Fortune\Output;

use Fortune\Output\HeaderInterface;

class JsonOutput
{
    protected $header;

    public function __construct(HeaderInterface $header)
    {
        $this->header = $header;
    }

    public function prepare($input = null)
    {
        $this->header->setJsonContentType();

        if (!is_array($input) && $input !== null) {
            $input = [$input => ""];
        }

        return !$input || count($input) === 0 ? '[]':json_encode($input);
    }
}
