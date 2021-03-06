<?php

use Fortune\Test\Container;
use Fortune\Configuration\Configuration;
use Fortune\Configuration\ResourceConfiguration;
use Fortune\ResourceFactory;
use Slim\Slim;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/../../../_bootstrap/bootstrap.php';

// for testing..this needs to have better implementation
if (isset($_GET['doLogin'])) { $_SESSION['username'] = 'foo'; }
if (isset($_GET['haveRole'])) { $_SESSION['role'] = $_GET['haveRole']; }

$container = new Container;
$app = new Slim;

$configuration = Yaml::parse(file_get_contents(PROJECT_ROOT . '/features/bootstrap/fixtures/config.yaml'));

$factory = new ResourceFactory($container->pdo, $configuration);
$factory->generateSlimRoutes($app);

$app->get('/', function () {
    echo 'home';
});

// this route is used to test a security feature
$app->get('/puppies', function () use ($factory, $app) {
    // this should be error
    $output = $factory->newSlimOutput($app->request, $app->response);
    echo $output->index();
});


$app->run();
