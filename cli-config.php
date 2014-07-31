<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'tests/acceptance/bootstrap/bootstrap.php';

return ConsoleRunner::createHelperSet($container['doctrine']);
