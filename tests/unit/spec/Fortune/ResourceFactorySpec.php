<?php

namespace spec\Fortune;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Configuration\Configuration;
use PDO;


class ResourceFactorySpec extends ObjectBehavior
{
    function let(PDO $database)
    {
        $this->beConstructedWith($database, array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\ResourceFactory');
    }
}
