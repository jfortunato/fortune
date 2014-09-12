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

    function it_should_determine_if_class_validator_is_used()
    {
        $this->beConstructedWith('foo', ['validation' => 'StdClass']);
        $this->isUsingYamlValidation()->shouldReturn(false);
    }

    function it_should_get_yaml_validation()
    {
        $this->beConstructedWith('foo', ['validation' => ['bar' => 'required']]);
        $this->getYamlValidation()->shouldReturn(['bar' => 'required']);
    }

    function it_should_set_default_configurations_when_none_are_given()
    {
        $this->beConstructedWith('foo', null);

        $this->getParent()->shouldReturn(null);
        $this->getEntityClass()->shouldReturn(null);
        $this->getYamlValidation()->shouldReturn(array());
        $this->requiresAuthentication()->shouldReturn(false);
        $this->requiresRole()->shouldReturn(null);
        $this->requiresOwner()->shouldReturn(false);
    }

    function it_should_set_default_configurations_when_some_are_given()
    {
        $this->beConstructedWith('foo', ['parent' => 'bar']);

        $this->getParent()->shouldReturn('bar');
        $this->getEntityClass()->shouldReturn(null);
        $this->getYamlValidation()->shouldReturn(array());
        $this->requiresAuthentication()->shouldReturn(false);
        $this->requiresRole()->shouldReturn(null);
        $this->requiresOwner()->shouldReturn(false);
    }

    function it_should_set_all_default_access_controls_when_one_is_given()
    {
        $this->beConstructedWith('foo', ['access_control' => ['owner' => true]]);

        $this->requiresAuthentication()->shouldReturn(false);
        $this->requiresRole()->shouldReturn(null);
        $this->requiresOwner()->shouldReturn(true);
    }
}
