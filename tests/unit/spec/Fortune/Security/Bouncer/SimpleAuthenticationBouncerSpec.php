<?php

namespace spec\Fortune\Security\Bouncer\Driver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Security\ResourceInspectorInterface;

class SimpleAuthenticationBouncerSpec extends ObjectBehavior
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
        $this->shouldHaveType('Fortune\Security\Bouncer\Driver\SimpleAuthenticationBouncer');
    }

    function it_should_allow_if_no_auth_required($inspector)
    {
        $inspector->requiresAuthentication('Foo\Bar')->shouldBeCalled()->willReturn(false);

        $this->check('Foo\Bar')->shouldReturn(true);
    }

    function it_should_deny_if_auth_required_and_user_not_authenticated($inspector)
    {
        $inspector->requiresAuthentication('Foo\Bar')->shouldBeCalled()->willReturn(true);

        $this->check('Foo\Bar')->shouldReturn(false);
    }

    function it_should_allow_if_auth_required_and_user_authenticated($inspector)
    {
        $inspector->requiresAuthentication('Foo\Bar')->shouldBeCalled()->willReturn(true);

        $_SESSION['username'] = 'foo';

        $this->check('Foo\Bar')->shouldReturn(true);
    }
}
