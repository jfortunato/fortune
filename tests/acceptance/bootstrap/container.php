<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Fortune\Repository\Driver\DoctrineResourceRepository;
use Fortune\Serializer\Driver\JMSSerializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use Fortune\Serializer\Driver\JMSPropertyExcluder;
use Fortune\Output\Driver\SlimOutput;
use Fortune\Test\Validator\DogValidator;
use Fortune\Security\Security;
use Fortune\Security\ResourceInspector;
use Fortune\Security\Bouncer\Driver\SimpleAuthenticationBouncer;
use Fortune\Security\Bouncer\Driver\SimpleRoleBouncer;
use Fortune\Resource\Resource;

$app->container->singleton('doctrine', function ()
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
});

$app->repository = function ($app)
{
    return new DoctrineResourceRepository($app->doctrine, 'Fortune\Test\Entity\Dog');
};

$app->serializer = function ($app)
{
    return new JMSSerializer(
        SerializerBuilder::create()->build(),
        new SerializationContext,
        new JMSPropertyExcluder
    );
};

$app->output = function ($app)
{
    return new SlimOutput($app->response);
};

$app->validator = function ()
{
    return new DogValidator;
};

$app->inspector = function ()
{
    return new ResourceInspector;
};

$app->authBouncer = function ($app)
{
    return new SimpleAuthenticationBouncer($app->inspector);
};

$app->roleBouncer = function ($app)
{
    return new SimpleRoleBouncer($app->inspector);
};

$app->security = function ($app)
{
    return new Security(
        $app->authBouncer,
        $app->roleBouncer
    );
};

$app->resource = function ($app)
{
    return new Resource(
        $app->repository,
        $app->serializer,
        $app->output,
        $app->validator,
        $app->security
    );
};
