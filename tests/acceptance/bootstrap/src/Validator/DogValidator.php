<?php

namespace Fortune\Test\Validator;

use Fortune\Validator\ResourceValidator;

class DogValidator extends ResourceValidator
{
    public function validate(array $input)
    {
        return array_key_exists('name', $input);
    }
}
