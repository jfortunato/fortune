<?php

namespace Fortune\Output;

class Header implements HeaderInterface
{
    public function setJsonContentType()
    {
        header('Content-Type: application/json');
    }
}
