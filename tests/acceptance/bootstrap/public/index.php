<?php

require_once __DIR__ . '/../bootstrap.php';

$uri = $_SERVER['REQUEST_URI'];

$repository = new Fortune\Repository\Driver\DoctrineResourceRepository($container['doctrine'], 'Fortune\Test\Entity\Dog');
$serializer = new Fortune\Serializer\Driver\JMSSerializer(JMS\Serializer\SerializerBuilder::create()->build());
$output = new Fortune\Output\Driver\SimpleOutput;
$resource = new Fortune\Resource\Resource($repository, $serializer, $output);

// routing is out of the scope of this library
if ($uri === '/dogs') {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        echo $resource->index();
    } else if ($_SERVER['REQUEST_METHOD'] === "POST") {
        echo $resource->create($_POST);
    }
} else if (preg_match('/^\/dogs\/(\d)$/', $uri, $id)) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        echo $resource->show($id[1]);
    } else if ($_SERVER['REQUEST_METHOD'] === "PUT") {
        parse_str(file_get_contents("php://input"), $input);
        echo $resource->update($id[1], $input);
    } else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
        echo $resource->delete($id[1]);
    }
}
