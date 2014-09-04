<?php

use Fortune\Configuration\Configuration;
use Fortune\Configuration\ResourceConfiguration;
use Fortune\ResourceFactory;

require_once __DIR__ . '/../../../_bootstrap/bootstrap.php';

$container = new Fortune\Test\Container;

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

if (isset($_GET['requiresOwner'])) {
    $reflectionClass = new \ReflectionClass('Fortune\Test\Entity\Dog');
    $reflectionClass->setStaticPropertyValue('requiresOwner', true);
}

$app = $container->getSlim();

$configuration = new Configuration(array(
    array(
        'name' => 'dogs',
        'entity' => 'Fortune\Test\Entity\Dog',
        'validator' => 'Fortune\Test\Validator\DogValidator',
    ),
    array(
        'name' => 'puppies',
        'entity' => 'Fortune\Test\Entity\Puppy',
        'validator' => 'Fortune\Test\Validator\PuppyValidator',
        'parent' => 'dogs',
    ),
));

$factory = new ResourceFactory($app->doctrine, $configuration);
$output = $factory->newSlimOutput($app->response);

parse_str($app->request->getBody(), $input);

$app->get('/dogs', function () use ($output)
{
    echo $output->index();
});

$app->post('/dogs', function () use ($output, $input)
{
    echo $output->create($input);
});

$app->get('/dogs/:id', function ($id) use ($output)
{
    echo $output->show($id);
});

$app->put('/dogs/:id', function ($id) use ($output, $input)
{
    echo $output->update($id, $input);
});

$app->delete('/dogs/:id', function ($id) use ($output)
{
    echo $output->delete($id);
});

$app->get('/puppies', function () use ($output)
{
    // this should be error
    echo $output->index();
});

$app->get('/dogs/:dog/puppies', function ($dog) use ($output)
{
    echo $output->index($dog);
});

$app->get('/dogs/:dog/puppies/:id', function ($dog, $id) use ($output)
{
    echo $output->show($id, $dog);
});

$app->post('/dogs/:dog/puppies', function ($dog) use ($output, $input)
{
    echo $output->create($input, $dog);
});

$app->put('/dogs/:dog/puppies/:id', function ($dog, $id) use ($output, $input)
{
    echo $output->update($id, $input, $dog);
});

$app->delete('/dogs/:dog/puppies/:id', function ($dog, $id) use ($output)
{
    echo $output->delete($id, $dog);
});


$app->run();
