<?php

use Behat\Behat\Context\BehatContext;
use Doctrine\ORM\Tools\SchemaTool;
use Fortune\Test\Container;

abstract class BaseContext extends BehatContext
{
    protected $container;

    public function __construct()
    {
        $this->container = new Container;
    }

    public function getManager()
    {
        return $this->container->doctrine;
    }
}
