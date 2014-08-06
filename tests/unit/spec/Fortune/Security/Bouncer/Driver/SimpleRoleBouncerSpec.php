<?php

namespace spec\Fortune\Security\Bouncer\Driver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Security\ResourceInspectorInterface;

class SimpleRoleBouncerSpec extends ObjectBehavior
{
    function let(ResourceInspectorInterface $inspector)
    {
        $this->beConstructedWith($inspector);
    }

    function letgo()
    {
        if (isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Security\Bouncer\Driver\SimpleRoleBouncer');
    }

    function it_should_allow_if_no_role_required($inspector)
    {
        $inspector->requiredRole('Foo\Bar')->shouldBeCalled()->willReturn(null);

        $this->check('Foo\Bar')->shouldReturn(true);
    }

    function it_should_deny_if_role_required_and_user_doesnt_have_that_role($inspector)
    {
        $inspector->requiredRole('Foo\Bar')->shouldBeCalled()->willReturn('foo');

        $this->check('Foo\Bar')->shouldReturn(false);
    }

    function it_should_allow_if_role_required_and_user_has_that_role($inspector)
    {
        $inspector->requiredRole('Foo\Bar')->shouldBeCalled()->willReturn('foo');

        $_SESSION['role'] = 'foo';

        $this->check('Foo\Bar')->shouldReturn(true);
    }
}
