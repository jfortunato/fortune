<?php

use Fortune\Test\Container;
use Fortune\Configuration\Configuration;
use Fortune\Configuration\ResourceConfiguration;
use Fortune\ResourceFactory;
use Slim\Slim;

require_once __DIR__ . '/../../../_bootstrap/bootstrap.php';

if (isset($_GET['doLogin'])) {
    $_SESSION['username'] = 'foo';
}

if (isset($_GET['haveRole'])) {
    $_SESSION['role'] = $_GET['haveRole'];
}

// this is only for testing purposes
$requiresAuthentication = isset($_GET['requiresAuthentication']);
$requiresRole = isset($_GET['requiresRole']) ? $_GET['requiresRole'] : null;
$requiresOwner = isset($_GET['requiresOwner']);

$container = new Container;
$app = new Slim;

$configuration = require __DIR__ . '/../config/config.php';

$factory = new ResourceFactory($container->doctrine, $configuration);
$factory->generateSlimRoutes($app);

// this route is used to test a security feature
$app->get('/puppies', function () use ($factory, $app) {
    // this should be error
    $output = $factory->newSlimOutput($app->request, $app->response);
    echo $output->index();
});


$app->run();
