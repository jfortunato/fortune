<?php

namespace spec\Fortune\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Security\Bouncer\AuthenticationBouncer;
use Fortune\Security\Bouncer\RoleBouncer;

class SecuritySpec extends ObjectBehavior
{
    function let(AuthenticationBouncer $authBouncer, RoleBouncer $roleBouncer)
    {
        $this->beConstructedWith($authBouncer, $roleBouncer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fortune\Security\Security');
    }

    function it_should_return_false_when_just_one_bouncer_returns_false($authBouncer, $roleBouncer)
    {
        $authBouncer->check('Foo\Bar')->willReturn(false);
        $roleBouncer->check('Foo\Bar')->willReturn(true);

        $this->isAllowed('Foo\Bar')->shouldReturn(false);;

        $authBouncer->check('Foo\Bar')->willReturn(true);
        $roleBouncer->check('Foo\Bar')->willReturn(false);

        $this->isAllowed('Foo\Bar')->shouldReturn(false);;
    }

    function it_should_return_true_only_when_all_bouncers_return_true($authBouncer, $roleBouncer)
    {
        $authBouncer->check('Foo\Bar')->willReturn(true);
        $roleBouncer->check('Foo\Bar')->willReturn(true);

        $this->isAllowed('Foo\Bar')->shouldReturn(true);
    }
}
