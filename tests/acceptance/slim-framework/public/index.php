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

$configuration = new Configuration(array(
    'dogs'      => array(
        'entity'             => 'Fortune\Test\Entity\Dog',
        'validator'          => 'Fortune\Test\Validator\DogValidator',
        'parent'             => '',
        'access_control'     => array(
            'authentication'    => $requiresAuthentication,
            'role'              => $requiresRole,
            'owner'             => $requiresOwner,
        ),
    ),
    'puppies'   => array(
        'entity'             => 'Fortune\Test\Entity\Puppy',
        'validator'          => 'Fortune\Test\Validator\PuppyValidator',
        'parent'             => 'dogs',
    ),
));

$factory = new ResourceFactory($container->doctrine, $configuration);
$output = $factory->newSlimOutput($app->request, $app->response);

$app->get('/dogs', function () use ($output) {
    echo $output->index();
});

$app->post('/dogs', function () use ($output) {
    echo $output->create();
});

$app->get('/dogs/:id', function ($id) use ($output) {
    echo $output->show($id);
});

$app->put('/dogs/:id', function ($id) use ($output) {
    echo $output->update($id);
});

$app->delete('/dogs/:id', function ($id) use ($output) {
    echo $output->delete($id);
});

$app->get('/puppies', function () use ($output) {
    // this should be error
    echo $output->index();
});

$app->get('/dogs/:dog/puppies', function ($dog) use ($output) {
    echo $output->index($dog);
});

$app->get('/dogs/:dog/puppies/:id', function ($dog, $id) use ($output) {
    echo $output->show($id, $dog);
});

$app->post('/dogs/:dog/puppies', function ($dog) use ($output) {
    echo $output->create($dog);
});

$app->put('/dogs/:dog/puppies/:id', function ($dog, $id) use ($output) {
    echo $output->update($id, $dog);
});

$app->delete('/dogs/:dog/puppies/:id', function ($dog, $id) use ($output) {
    echo $output->delete($id, $dog);
});


$app->run();
