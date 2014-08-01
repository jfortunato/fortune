<?php

namespace Fortune\Validator;

abstract class ResourceValidator
{
    abstract public function validate(array $input);
}
