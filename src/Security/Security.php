<?php

namespace Fortune\Security;

use Fortune\Security\Bouncer\AuthenticationBouncer;
use Fortune\Security\Bouncer\RoleBouncer;
use Fortune\Security\Bouncer\ParentBouncer;

/**
 * This is the gateway to checking resource accessibility.
 * Stores a collection of Bouncer objects, which do the work of
 * actually checking accessibility.
 *
 * @package Fortune
 */
class Security implements SecurityInterface
{
    /**
     * All the Bouncer objects to determine resource accessibility.
     *
     * @var array Collection of Bouncer objects
     */
    protected $bouncers = array();

    /**
     * Constructor
     *
     * @param AuthenticationBouncer $authenticationBouncer
     * @param RoleBouncer $roleBouncer
     * @param ParentBouncer $parentBouncer
     * @return void
     */
    public function __construct(
        AuthenticationBouncer $authenticationBouncer,
        RoleBouncer $roleBouncer,
        ParentBouncer $parentBouncer
    ) {
        $this->bouncers[] = $authenticationBouncer;
        $this->bouncers[] = $roleBouncer;
        $this->bouncers[] = $parentBouncer;
    }

    /**
     * @Override
     */
    public function isAllowed()
    {
        foreach ($this->bouncers as $bouncer) {
            if (!$bouncer->check()) {
                return false;
            }
        }

        return true;
    }
}
