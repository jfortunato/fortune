<?php

namespace spec\Fortune\Security\Bouncer\Driver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Security\ResourceInspectorInterface;

class SimpleOwnerBouncerSpec extends ObjectBehavior
{
    function let(ResourceInspectorInterface $inspector)
    {
        $this->beConstructedWith($inspector);
    }

    function letgo()
    {
        if (isset($_SESSION['username'])) {
            unset($_SESSION['username']);
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Security\Bouncer\Driver\SimpleOwnerBouncer');
    }

    function it_should_allow_if_owner_not_required($inspector)
    {
        $inspector->requiresOwner('Foo\Bar')->shouldBeCalled()->willReturn(false);

        $this->check('Foo\Bar')->shouldReturn(true);
    }

    function it_should_deny_if_owner_required_and_we_are_not_the_owner($inspector)
    {
        $inspector->requiresOwner('Foo\Bar')->shouldBeCalled()->willReturn(true);

        $this->check('Foo\Bar')->shouldReturn(false);
    }

    function it_should_allow_if_owner_required_and_we_are_the_owner($inspector)
    {
        $inspector->requiresOwner('Foo\Bar')->shouldBeCalled()->willReturn(true);

        $_SESSION['username'] = 'foo';

        $this->check('Foo\Bar')->shouldReturn(true);
    }
}
