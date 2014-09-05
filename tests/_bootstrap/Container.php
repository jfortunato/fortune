<?php

namespace Fortune\Test;

use Slim\Slim;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

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
            $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../acceptance/slim-framework/src/Entity"), $isDevMode);

            // database configuration parameters
            $conn = array(
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/../db.sqlite',
            );

            // obtaining the entity manager
            return EntityManager::create($conn, $config);
        });

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
