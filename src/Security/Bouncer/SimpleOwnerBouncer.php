<?php

namespace Fortune\Security\Bouncer;

use Fortune\Security\Bouncer\OwnerBouncer;

class SimpleOwnerBouncer extends OwnerBouncer
{
    public function isOwner($entity)
    {
        return isset($_SESSION['username']);
    }
}
