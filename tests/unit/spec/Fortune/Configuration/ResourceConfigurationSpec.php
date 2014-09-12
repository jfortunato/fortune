<?php

namespace spec\Fortune\Configuration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Fortune\Configuration\fixtures\Puppy;

class ResourceConfigurationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo', array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Configuration\ResourceConfiguration');
    }

    function it_should_determine_if_yaml_validation_is_used()
    {
        $this->beConstructedWith('foo', ['validation' => ['bar' => 'required']]);
        $this->isUsingYamlValidation()->shouldReturn(true);
    }

    function it_should_determince_if_class_validator_is_ised()
    {
        $this->beConstructedWith('foo', ['validation' => 'StdClass']);
        $this->isUsingYamlValidation()->shouldReturn(false);
    }

    function it_should_get_yaml_validation()
    {
        $this->beConstructedWith('foo', ['validation' => ['bar' => 'required']]);
        $this->getYamlValidation()->shouldReturn(['bar' => 'required']);
    }
}
