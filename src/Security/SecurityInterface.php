<?php

namespace Fortune\Security;

interface SecurityInterface
{
    public function isAllowed($entityClass);
}
