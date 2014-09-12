<?php

namespace spec\Fortune\Validator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YamlResourceValidatorSpec extends ObjectBehavior
{
    function let($v)
    {
        $v->beADoubleOf('Valitron\Validator');
        $this->beConstructedWith(array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Validator\YamlResourceValidator');
    }

    function it_should_be_a_valitron_resource_validator()
    {
        $this->shouldBeAnInstanceOf('Fortune\Validator\ValitronResourceValidator');
    }

    function it_should_add_rules_to_the_validator($v)
    {
        $this->beConstructedWith(['foo' => 'required', 'bar' => 'required']);

        $v->rule('required', 'foo')->shouldBeCalled();
        $v->rule('required', 'bar')->shouldBeCalled();

        $this->addRules($v);
    }

    function it_should_expand_pipe_delimited_rules_into_array($v)
    {
        $this->beConstructedWith(['foo' => 'required|numeric']);

        $v->rule('required', 'foo')->shouldBeCalled();
        $v->rule('numeric', 'foo')->shouldBeCalled();

        $this->addRules($v);
    }

    function it_should_ignore_whitespace_when_expanding_rules($v)
    {
        $this->beConstructedWith(['foo' => ' required | numeric ']);

        $v->rule('required', 'foo')->shouldBeCalled();
        $v->rule('numeric', 'foo')->shouldBeCalled();

        $this->addRules($v);
    }

    function it_should_add_rules_with_colon_argument($v)
    {
        $this->beConstructedWith(['foo' => 'minLength:3']);

        $v->rule('minLength', 'foo', '3')->shouldBeCalled();

        $this->addRules($v);
    }
}
