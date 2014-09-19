<?php

namespace Fortune\Validator;

use Valitron\Validator;

/**
 * Takes validation rules that were given through configuration
 * and creates Valitron rules for them.
 *
 * @package Fortune
 */
class YamlResourceValidator extends ValitronResourceValidator
{
    /**
     * The rules that were configured through yaml.
     *
     * @var array
     */
    protected $rules;

    /**
     * Constructor
     *
     * @param array $rules
     * @return void
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @Override
     */
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

    /**
     * A given field can have multiple rules, which are separated by
     * the pipe character. This method puts all rules into their own
     * container. So "required|numeric" becomes ["required", "numeric"].
     *
     * @param string $rules
     * @return array
     */
    protected function expandRules($rules)
    {
        return explode('|', preg_replace('/\s+/', '', $rules));
    }

    /**
     * Some rules have arguments, which are separated with the colon
     * character. If a rule has an argument, this method will return an
     * associative array in the form ["rule" => $rule, "argument" => $argument].
     *
     * @param string $rule
     * @return array|null
     */
    protected function getRuleArgument($rule)
    {
        $exploded = explode(':', $rule);

        return isset($exploded[1]) ?
            array('rule' => $exploded[0], 'argument' => $exploded[1]) : null;
    }
}
