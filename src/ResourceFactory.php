<?php

namespace Fortune;

use Fortune\Configuration\Configuration;
use Doctrine\ORM\EntityManager;
use PDO;
use Slim\Http\Response;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use Fortune\Configuration\ResourceConfiguration;
use Fortune\Resource;
use Fortune\Security\ResourceInspector;
use Fortune\Security\Security;
use Fortune\Security\Bouncer\ParentBouncer;
use Fortune\Output\SimpleOutput;
use Fortune\Output\SlimOutput;
use Fortune\Serializer\JMSSerializer;
use Fortune\Serializer\JMSPropertyExcluder;
use Fortune\Security\Bouncer\SimpleAuthenticationBouncer;
use Fortune\Security\Bouncer\SimpleRoleBouncer;
use Fortune\Security\Bouncer\SimpleOwnerBouncer;
use Fortune\Repository\DoctrineResourceRepository;
use Slim\Http\Request;
use Slim\Slim;
use Fortune\Routing\SlimRouteGenerator;
use Fortune\Repository\PdoResourceRepository;
use Fortune\Validator\YamlResourceValidator;

/**
 * Factory for creating this packages objects.
 *
 * @package Fortune
 */
class ResourceFactory
{
    /**
     * Depending preffered database connection.
     *
     * @var EntityManager|PDO
     */
    protected $database;

    /**
     * Holds all the ResourceConfiguration objects
     *
     * @var Configuration
     */
    protected $config;

    /**
     * Constructor
     *
     * @param mixed $database
     * @param array $config
     * @return void
     */
    public function __construct($database, array $config)
    {
        $this->database = $database;
        $this->config = new Configuration($config);
    }

    /**
     * Creates a SimpleOutput instance to be used with native PHP.
     *
     * @return SimpleOutput
     */
    public function newSimpleOutput()
    {
        return new SimpleOutput(
            $this->newSerializer(),
            $this->newResource($this->config->getCurrentResourceConfiguration())
        );
    }

    /**
     * Creates a SlimOutput instance to be used with the slim PHP framework.
     *
     * @param Request $request
     * @param Response $response
     * @return SlimOutput
     */
    public function newSlimOutput(Request $request, Response $response)
    {
        return new SlimOutput(
            $request,
            $response,
            $this->newSerializer(),
            $this->newResource($this->config->getCurrentResourceConfiguration())
        );
    }

    public function generateSlimRoutes(Slim $slim)
    {
        // if we are not on a resources's route, then dont generate any routes
        if (!$this->config->getCurrentResourceConfiguration()) {
            return;
        }

        $output = $this->newSlimOutput($slim->request, $slim->response);

        $generator = new SlimRouteGenerator($slim, $output, $this->config);

        $generator->generateRoutes();
    }

    /**
     * Getter for Configuration
     *
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Finds a ResourceConfiguration for given resource name.
     *
     * @param string $resourceName
     * @return ResourceConfiguration
     */
    public function resourceFor($resourceName)
    {
        $resourceConfig = $this->config->resourceConfigurationFor($resourceName);

        return $this->newResource($resourceConfig);
    }

    /**
     * Repository to be used for doctrine.
     *
     * @param EntityManager $manager
     * @param string $entityClass
     * @return DoctrineResourceRepository
     */
    protected function newDoctrineRepository(EntityManager $manager, $entityClass, $parent)
    {
        return new DoctrineResourceRepository($manager, $entityClass, $parent);
    }

    /**
     * Repository to be used for PDO
     *
     * @param PDO $pdo
     * @param mixed $entityClass
     * @return PdoResourceRepository
     */
    protected function newPdoRepository(PDO $pdo, $entityClass, $parentCol)
    {
        return new PdoResourceRepository($pdo, $entityClass, $parentCol);
    }

    /**
     * Determines which repository to use based on the database connection
     * that was passed in.
     *
     * @param string $entityClass
     * @return ResourceRepositoryInterface
     */
    protected function newRepository(ResourceConfiguration $config)
    {
        if ($this->database instanceof EntityManager) {
            return $this->newDoctrineRepository($this->database, $config->getEntityClass(), $config->getParent());
        } elseif ($this->database instanceof PDO) {
            return $this->newPdoRepository($this->database, $config->getResource(), $config->getParent());
        }

        throw new \Exception("Couldn't determine database connection type.");
    }

    /**
     * Creates JMSSerializer instance.
     *
     * @return JMSSerializer
     */
    protected function newSerializer()
    {
        return new JMSSerializer(
            SerializerBuilder::create()->build(),
            new SerializationContext,
            new JMSPropertyExcluder
        );
    }

    /**
     * Creates a new Resource based off its ResourceConfiguration
     *
     * @param ResourceConfiguration $config
     * @return Resource
     */
    protected function newResource(ResourceConfiguration $config)
    {
        return new Resource(
            $this->newRepository($config),
            $this->newValidator($config),
            $this->newSecurity(),
            $this
        );
    }

    protected function newValidator(ResourceConfiguration $config)
    {
        $class = $config->getValidatorClass();

        return $config->isUsingYamlValidation() ?
            new YamlResourceValidator($config->getYamlValidation()) : new $class();
    }

    /**
     * Creates a Security gateway with all Bouncers
     *
     * @return Security
     */
    protected function newSecurity()
    {
        $config = $this->config->getCurrentResourceConfiguration();

        return new Security(
            new SimpleAuthenticationBouncer($config),
            new SimpleRoleBouncer($config),
            new SimpleOwnerBouncer($config),
            new ParentBouncer($config)
        );
    }
}
