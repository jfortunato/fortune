<?php

namespace Fortune\Security\Bouncer;

/**
 * Determines resource accessibility based on login status. Subclass
 * determines implementaion.
 *
 * @package Fortune
 */
abstract class AuthenticationBouncer extends Bouncer
{
    /**
     * Determine if user is authenticated.
     *
     * @return boolean
     */
    abstract public function isAuthenticated();

    /**
     * @Override
     */
    public function check($method, $resource, $identifiers = null)
    {
        return $this->config->requiresAuthentication() ?  $this->isAuthenticated() : true;
    }
}
