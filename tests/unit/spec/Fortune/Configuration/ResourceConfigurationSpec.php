<?php

namespace spec\Fortune\Configuration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Configuration\ResourceConfiguration');
    }
}
