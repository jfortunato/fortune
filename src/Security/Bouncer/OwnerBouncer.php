<?php

namespace Fortune\Security\Bouncer;

/**
 * Determines resource accessibility based on whether or not the user
 * is the resource owner. Subclass determines implementaion.
 *
 * @package Fortune
 */
abstract class OwnerBouncer extends Bouncer
{
    /**
     * Checks if user is an owner of the resource.
     *
     * @return boolean
     */
    abstract public function isOwner();

    /**
     * @Override
     */
    public function check()
    {
        return $this->config->requiresOwner() ?  $this->isOwner() : true;
    }
}
