<?php

namespace test\Fortune\Validator\fixtures;

use Fortune\Validator\Driver\ValitronResourceValidator as BaseValidator;
use Valitron\Validator;

class ValitronResourceValidator extends BaseValidator
{
    protected $rules = [];

    public function addRules(Validator $v)
    {
        $v->rules($this->rules);
    }

    public function addRule($rule, $field)
    {
        $this->rules[$rule] = $field;
    }
}
