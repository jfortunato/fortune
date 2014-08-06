<?php

require_once __DIR__ . '/../bootstrap.php';

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

parse_str($app->request->getBody(), $input);
$resource = $app->resource;

$app->get('/dogs', function () use ($resource)
{
    echo $resource->index();
});

$app->post('/dogs', function () use ($resource, $input)
{
    echo $resource->create($input);
});

$app->get('/dogs/:id', function ($id) use ($resource)
{
    echo $resource->show($id);
});

$app->put('/dogs/:id', function ($id) use ($resource, $input)
{
    echo $resource->update($id, $input);
});

$app->delete('/dogs/:id', function ($id) use ($resource)
{
    echo $resource->delete($id);
});

$app->run();
