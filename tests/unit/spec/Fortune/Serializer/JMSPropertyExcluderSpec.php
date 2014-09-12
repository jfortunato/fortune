<?php

namespace spec\Fortune\Serializer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Context;

class JMSPropertyExcluderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Serializer\JMSPropertyExcluder');
    }
}
