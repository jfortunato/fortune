<?php

namespace Fortune\Security\Bouncer;

use Fortune\Security\Bouncer\OwnerBouncer;

/**
 * Implementation of OwnerBouncer
 *
 * @package Fortune
 */
class SimpleOwnerBouncer extends OwnerBouncer
{
    /**
     * @Override
     */
    public function isOwner()
    {
        return isset($_SESSION['username']);
    }
}
