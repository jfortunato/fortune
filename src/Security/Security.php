<?php

namespace Fortune\Security;

use Fortune\Security\Bouncer\AuthenticationBouncer;
use Fortune\Security\Bouncer\RoleBouncer;

class Security implements SecurityInterface
{
    protected $bouncers = array();

    public function __construct(AuthenticationBouncer $authenticationBouncer, RoleBouncer $roleBouncer)
    {
        $this->bouncers[] = $authenticationBouncer;
        $this->bouncers[] = $roleBouncer;
    }

    public function isAllowed($entityClass)
    {
        foreach ($this->bouncers as $bouncer)
        {
            if (!$bouncer->check($entityClass))
            {
                return false;
            }
        }

        return true;
    }
}
