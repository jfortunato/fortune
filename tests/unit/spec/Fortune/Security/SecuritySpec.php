<?php

namespace spec\Fortune\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Security\Bouncer\AuthenticationBouncer;
use Fortune\Security\Bouncer\RoleBouncer;
use Fortune\Security\Bouncer\ParentBouncer;

class SecuritySpec extends ObjectBehavior
{
    function let(AuthenticationBouncer $authBouncer, RoleBouncer $roleBouncer, ParentBouncer $parentBouncer)
    {
        $this->beConstructedWith($authBouncer, $roleBouncer, $parentBouncer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Security\Security');
    }

    function it_should_return_false_when_just_one_bouncer_returns_false($authBouncer, $roleBouncer)
    {
        $authBouncer->check()->willReturn(false);
        $roleBouncer->check()->willReturn(true);

        $this->isAllowed()->shouldReturn(false);;

        $authBouncer->check()->willReturn(true);
        $roleBouncer->check()->willReturn(false);

        $this->isAllowed()->shouldReturn(false);;
    }

    function it_should_return_true_only_when_all_bouncers_return_true($authBouncer, $roleBouncer, $parentBouncer)
    {
        $authBouncer->check()->willReturn(true);
        $roleBouncer->check()->willReturn(true);
        $parentBouncer->check()->willReturn(true);

        $this->isAllowed()->shouldReturn(true);
    }
}
