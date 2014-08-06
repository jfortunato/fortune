<?php

namespace Fortune\Security;

use Fortune\Security\Bouncer\AuthenticationBouncer;
use Fortune\Security\Bouncer\RoleBouncer;
use Fortune\Security\Bouncer\OwnerBouncer;

class Security implements SecurityInterface
{
    protected $bouncers = array();

    public function __construct(AuthenticationBouncer $authenticationBouncer, RoleBouncer $roleBouncer, OwnerBouncer $ownerBouncer)
    {
        $this->bouncers[] = $authenticationBouncer;
        $this->bouncers[] = $roleBouncer;
        $this->bouncers[] = $ownerBouncer;
    }

    public function isAllowed($entityOrClass)
    {
        foreach ($this->bouncers as $bouncer)
        {
            if (!$bouncer->check($entityOrClass))
            {
                return false;
            }
        }

        return true;
    }
}
