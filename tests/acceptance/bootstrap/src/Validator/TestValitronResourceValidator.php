<?php

namespace Fortune\Test\Validator;

use Fortune\Validator\Driver\ValitronResourceValidator;
use Valitron\Validator;

class TestValitronResourceValidator extends ValitronResourceValidator
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
