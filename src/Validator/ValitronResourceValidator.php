<?php

namespace Fortune\Validator;

use Fortune\Validator\ResourceValidatorInterface;
use Valitron\Validator;

/**
 * User should derive their own class from this and add validation rules.
 *
 * @package Fortune
 */
abstract class ValitronResourceValidator implements ResourceValidatorInterface
{
    /**
     * User supplied rules for validation.
     *
     * @param Validator $v
     * @return void
     */
    abstract public function addRules(Validator $v);

    /**
     * @Override
     */
    public function validate(array $input)
    {
        $valitron = new Validator($input);

        $this->addRules($valitron);

        return $valitron->validate();
    }
}
