<?php

use Doctrine\ORM\Tools\SchemaTool;
use Fortune\Test\Container;
use Behat\Behat\Context\Context;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

abstract class BaseContext implements Context
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
