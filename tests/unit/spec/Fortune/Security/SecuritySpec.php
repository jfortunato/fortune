<?php

namespace spec\Fortune\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Fortune\Security\Bouncer\AuthenticationBouncer;
use Fortune\Security\Bouncer\RoleBouncer;
use Fortune\Security\Bouncer\OwnerBouncer;

class SecuritySpec extends ObjectBehavior
{
    function let(AuthenticationBouncer $authBouncer, RoleBouncer $roleBouncer, OwnerBouncer $ownerBouncer)
    {
        $this->beConstructedWith($authBouncer, $roleBouncer, $ownerBouncer);
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

    function it_should_return_true_only_when_all_bouncers_return_true($authBouncer, $roleBouncer, $ownerBouncer)
    {
        $authBouncer->check('Foo\Bar')->willReturn(true);
        $roleBouncer->check('Foo\Bar')->willReturn(true);
        $ownerBouncer->check('Foo\Bar')->willReturn(true);

        $this->isAllowed('Foo\Bar')->shouldReturn(true);
    }
}
