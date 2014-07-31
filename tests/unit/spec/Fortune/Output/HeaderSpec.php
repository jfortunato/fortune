<?php

namespace spec\Fortune\Output;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class HeaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Output\Header');
    }

    function it_implements_headerinterface()
    {
        $this->shouldImplement('Fortune\Output\HeaderInterface');
    }
}
