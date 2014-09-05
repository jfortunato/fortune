<?php

namespace Fortune\Security\Bouncer;

abstract class OwnerBouncer extends Bouncer
{
    abstract public function isOwner($entity);

    public function check($entityOrClass)
    {
        return $this->config->requiresOwner($this->getEntityClass($entityOrClass)) ?
            $this->isOwner($entityOrClass) : true;
    }
}
