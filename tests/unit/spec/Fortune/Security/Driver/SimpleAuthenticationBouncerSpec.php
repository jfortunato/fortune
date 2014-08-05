<?php

namespace spec\Fortune\Security\Driver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Security\ResourceInspectorInterface;

class SimpleAuthenticationBouncerSpec extends ObjectBehavior
{
    function let(ResourceInspectorInterface $inspector)
    {
        $this->beConstructedWith($inspector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Security\Driver\SimpleAuthenticationBouncer');
    }

    function it_should_implement_securityinterface()
    {
        $this->shouldImplement('Fortune\Security\SecurityInterface');
    }

    function it_should_allow_if_no_auth_required($inspector)
    {
        $inspector->requiresAuthentication('Foo\Bar')->shouldBeCalled()->willReturn(false);

        $this->isAllowed('Foo\Bar')->shouldReturn(true);
    }

    function it_should_deny_if_auth_required_and_user_not_authenticated($inspector)
    {
        $inspector->requiresAuthentication('Foo\Bar')->shouldBeCalled()->willReturn(true);

        $this->isAllowed('Foo\Bar')->shouldReturn(false);
    }

    function it_should_allow_if_auth_required_and_user_authenticated($inspector)
    {
        $inspector->requiresAuthentication('Foo\Bar')->shouldBeCalled()->willReturn(true);

        $_SESSION['username'] = 'foo';

        $this->isAllowed('Foo\Bar')->shouldReturn(true);
    }
}
