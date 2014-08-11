<?php

namespace Fortune\Test;

use Slim\Slim;
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
use Fortune\Security\Bouncer\Driver\SimpleOwnerBouncer;
use Fortune\Resource\Resource;

class Container
{
    protected $slim;

    public function __construct(Slim $slim = null)
    {
        $this->slim = $slim ?: new Slim;

        $this->registerAll();
    }

    protected function registerAll()
    {
        $slim = $this->slim;

        $slim->container->singleton('doctrine', function () {
            $isDevMode = true;
            $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../acceptance/app-bootstrap/src/Entity"), $isDevMode);

            // database configuration parameters
            $conn = array(
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/../db.sqlite',
            );

            // obtaining the entity manager
            return EntityManager::create($conn, $config);
        });

        $slim->repository = function ($slim) {
            return new DoctrineResourceRepository($slim->doctrine, 'Fortune\Test\Entity\Dog');
        };

        $slim->serializer = function () {
            return new JMSSerializer(
                SerializerBuilder::create()->build(),
                new SerializationContext,
                new JMSPropertyExcluder
            );
        };

        $slim->output = function ($slim) {
            return new SlimOutput($slim->response);
        };

        $slim->validator = function () {
            return new DogValidator;
        };

        $slim->inspector = function () {
            return new ResourceInspector;
        };

        $slim->authBouncer = function ($slim) {
            return new SimpleAuthenticationBouncer($slim->inspector);
        };

        $slim->roleBouncer = function ($slim) {
            return new SimpleRoleBouncer($slim->inspector);
        };

        $slim->ownerBouncer = function ($slim) {
            return new SimpleOwnerBouncer($slim->inspector);
        };

        $slim->security = function ($slim) {
            return new Security(
                $slim->authBouncer,
                $slim->roleBouncer,
                $slim->ownerBouncer
            );
        };

        $slim->resource = function ($slim) {
            return new Resource(
                $slim->repository,
                $slim->serializer,
                $slim->output,
                $slim->validator,
                $slim->security
            );
        };

        $slim->dbRecreator = function ($slim) {
            return new DatabaseRecreator($slim->doctrine);
        };
    }

    public function __get($name)
    {
        return $this->slim->$name;
    }

    /**
     * Gets the value of slim
     *
     * @return Slim
     */
    public function getSlim()
    {
        return $this->slim;
    }
}
