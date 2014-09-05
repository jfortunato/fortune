<?php

namespace Fortune\Validator;

interface ResourceValidatorInterface
{
    /**
     * Checks if the input is valid for a resource.
     *
     * @param array $input
     * @return boolean
     */
    public function validate(array $input);
}
