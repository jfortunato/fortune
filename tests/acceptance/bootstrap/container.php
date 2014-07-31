<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$container = new Pimple\Container;

$container['doctrine'] = function ($c)
{
    $isDevMode = true;
    $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Entity"), $isDevMode);

    // database configuration parameters
    $conn = array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/db.sqlite',
    );

    // obtaining the entity manager
    return EntityManager::create($conn, $config);
};
