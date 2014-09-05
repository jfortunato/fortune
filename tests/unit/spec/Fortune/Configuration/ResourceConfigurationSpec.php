<?php

namespace spec\Fortune\Configuration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Fortune\Configuration\fixtures\Puppy;

class PuppyFixture {
    private $dog;
}

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

    function it_can_get_parent_entity_property()
    {
        $this->setEntityClass('spec\Fortune\Configuration\PuppyFixture');

        $this->setParent('dog');
        $this->getParentEntityProperty()->shouldReturn('dog');

        $this->setParent('dogs');
        $this->getParentEntityProperty()->shouldReturn('dog');
    }
}
