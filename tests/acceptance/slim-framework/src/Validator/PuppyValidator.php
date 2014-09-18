<?php

namespace Fortune\Test\Validator;

use Fortune\Validator\ValitronResourceValidator;
use Valitron\Validator;

class PuppyValidator extends ValitronResourceValidator
{
    protected $rules = array(
        'required' => 'name',
    );

    public function addRules(Validator $v)
    {
        $v->rules($this->rules);
    }
}
