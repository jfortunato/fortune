<?php

namespace Fortune\Test\Validator;

use Fortune\Validator\ValitronResourceValidator;
use Valitron\Validator;

class PuppyValidator extends ValitronResourceValidator
{
    protected $rules = [
        'required' => 'name',
    ];

    public function addRules(Validator $v)
    {
        $v->rules($this->rules);
    }
}
