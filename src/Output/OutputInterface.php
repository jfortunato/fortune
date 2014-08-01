<?php

namespace Fortune\Output;

interface OutputInterface
{
    public function response($content, $code);
}
