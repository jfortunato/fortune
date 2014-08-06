<?php

require_once __DIR__ . '/../bootstrap.php';

$uri = $_SERVER['REQUEST_URI'];

$repository = new Fortune\Repository\Driver\DoctrineResourceRepository($container['doctrine'], 'Fortune\Test\Entity\Dog');
$serializer = new Fortune\Serializer\Driver\JMSSerializer(JMS\Serializer\SerializerBuilder::create()->build(), new JMS\Serializer\SerializationContext, new Fortune\Serializer\Driver\JMSPropertyExcluder);
$output = new Fortune\Output\Driver\SimpleOutput;
$validator = new Fortune\Test\Validator\DogValidator;
$inspector = new Fortune\Security\ResourceInspector;
$security = new Fortune\Security\Security(new Fortune\Security\Bouncer\Driver\SimpleAuthenticationBouncer($inspector), new Fortune\Security\Bouncer\Driver\SimpleRoleBouncer($inspector));
$resource = new Fortune\Resource\Resource($repository, $serializer, $output, $validator, $security);

if (isset($_GET['requiresAuthentication'])) {
    $reflectionClass = new \ReflectionClass('Fortune\Test\Entity\Dog');
    $reflectionClass->setStaticPropertyValue('requiresAuthentication', true);
}

if (isset($_GET['doLogin'])) {
    $_SESSION['username'] = 'foo';
}

if (isset($_GET['haveRole'])) {
    $_SESSION['role'] = $_GET['haveRole'];
}

if (isset($_GET['requiresRole'])) {
    $reflectionClass = new \ReflectionClass('Fortune\Test\Entity\Dog');
    $reflectionClass->setStaticPropertyValue('requiresRole', $_GET['requiresRole']);
}

// routing is out of the scope of this library
if (preg_match('/^\/dogs\/(\d)/', $uri, $id)) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        echo $resource->show($id[1]);
    } else if ($_SERVER['REQUEST_METHOD'] === "PUT") {
        parse_str(file_get_contents("php://input"), $input);
        echo $resource->update($id[1], $input);
    } else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
        echo $resource->delete($id[1]);
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        echo $resource->index();
    } else if ($_SERVER['REQUEST_METHOD'] === "POST") {
        echo $resource->create($_POST);
    }
}
