<?php

require_once __DIR__ . '/../bootstrap.php';

$uri = $_SERVER['REQUEST_URI'];

$meta = new Doctrine\ORM\Mapping\ClassMetadata('Fortune\Test\Entity\Dog');

$dogRepo = new Fortune\Test\Entity\DogRepository($container['doctrine'], $meta);

$repository = new Fortune\Repository\DoctrineResourceRepository($dogRepo);
$serializer = new Fortune\Serializer\JMSSerializer(JMS\Serializer\SerializerBuilder::create()->build());
//$output = new Fortune\Output\JsonOutput(new Fortune\Output\Header);
$resource = new Fortune\Resource\Resource($repository, $serializer);

// routing is out of the scope of this library
header('Content-Type: application/json');
if ($uri === '/dogs') {
    echo $resource->index();
} else if ($uri === '/dogs/1') {
    echo $resource->show(1);
}
