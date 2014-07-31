<?php

require_once __DIR__ . '/../bootstrap.php';

$uri = $_SERVER['REQUEST_URI'];

$repository = new Fortune\Repository\DoctrineResourceRepository($container['doctrine'], 'Fortune\Test\Entity\Dog');
$serializer = new Fortune\Serializer\JMSSerializer(JMS\Serializer\SerializerBuilder::create()->build());
$output = new Fortune\Output\SimpleOutput;
$resource = new Fortune\Resource\Resource($repository, $serializer, $output);

// routing is out of the scope of this library
header('Content-Type: application/json');
if ($uri === '/dogs') {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        echo $resource->index();
    } else if ($_SERVER['REQUEST_METHOD'] === "POST") {
        echo $resource->create($_POST);
    }
} else if (preg_match('/^\/dogs\/(\d)$/', $uri, $id)) {
    echo $resource->show($id[1]);
}
