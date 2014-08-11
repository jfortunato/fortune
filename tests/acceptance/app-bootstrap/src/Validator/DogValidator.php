<?php

namespace Fortune\Test\Validator;

use Fortune\Validator\Driver\ValitronResourceValidator;
use Valitron\Validator;

class DogValidator extends ValitronResourceValidator
{
    protected $rules = [
        'required' => 'name',
    ];

    public function addRules(Validator $v)
    {
        $v->rules($this->rules);
    }
}
