<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Fortune\Test\Container;

require_once 'vendor/autoload.php';

$container = new Container;

return ConsoleRunner::createHelperSet($container->doctrine);
