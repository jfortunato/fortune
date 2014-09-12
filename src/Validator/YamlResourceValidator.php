<?php

namespace Fortune\Validator;

use Valitron\Validator;

class YamlResourceValidator extends ValitronResourceValidator
{
    protected $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function addRules(Validator $v)
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($this->expandRules($rules) as $rule) {
                if ($ruleWithArg = $this->getRuleArgument($rule)) {
                    $v->rule($ruleWithArg['rule'], $field, $ruleWithArg['argument']);
                } else {
                    $v->rule($rule, $field);
                }
            }
        }
    }

    protected function expandRules($rules)
    {
        return explode('|', preg_replace('/\s+/', '', $rules));
    }

    protected function getRuleArgument($rule)
    {
        $exploded = explode(':', $rule);

        return isset($exploded[1]) ?
            array('rule' => $exploded[0], 'argument' => $exploded[1]) : null;
    }
}
