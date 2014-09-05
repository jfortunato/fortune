<?php

namespace Fortune\Security;

interface SecurityInterface
{
    /**
     * Checks all security mechanisms to determine if resource is accessible.
     *
     * @return boolean
     */
    public function isAllowed();
}
