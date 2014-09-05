<?php

namespace Fortune\Validator;

use Fortune\Validator\ResourceValidatorInterface;
use Valitron\Validator;

abstract class ValitronResourceValidator implements ResourceValidatorInterface
{
    abstract public function addRules(Validator $v);

    public function validate(array $input)
    {
        $valitron = new Validator($input);

        $this->addRules($valitron);

        return $valitron->validate();
    }
}
