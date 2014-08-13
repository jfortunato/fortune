<?php

namespace spec\Fortune\Configuration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Configuration\ResourceConfiguration;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Configuration\Configuration');
    }

    function it_can_determine_which_resourceconfiguration_to_use_based_on_request(ResourceConfiguration $config)
    {
        $this->addResourceConfiguration($config);

        $config->getResource()->willReturn('dogs');

        $this->getResourceConfigurationFromRequest('/dogs')->shouldReturn($config);
        $this->getResourceConfigurationFromRequest('/dogs/1')->shouldReturn($config);
        $this->getResourceConfigurationFromRequest('/dogs?test=foo')->shouldReturn($config);
        $this->getResourceConfigurationFromRequest('/dogs/1?test=foo')->shouldReturn($config);
    }

    function it_can_determine_which_resourceconfiguration_to_use_based_on_request_when_multi_config(ResourceConfiguration $config1, ResourceConfiguration $config2)
    {
        $this->addResourceConfiguration($config1);
        $this->addResourceConfiguration($config2);

        $config1->getResource()->willReturn('dogs');
        $config2->getResource()->willReturn('puppies');

        $this->getResourceConfigurationFromRequest('/dogs')->shouldReturn($config1);
        $this->getResourceConfigurationFromRequest('/puppies')->shouldReturn($config2);
        $this->getResourceConfigurationFromRequest('/dogs/1/puppies')->shouldReturn($config2);
    }

    function it_returns_null_if_resourceconfiguration_not_found_from_request(ResourceConfiguration $config)
    {
        $this->addResourceConfiguration($config);

        $config->getResource()->willReturn('foo');

        $this->getResourceConfigurationFromRequest('/dogs')->shouldReturn(null);
    }
}
