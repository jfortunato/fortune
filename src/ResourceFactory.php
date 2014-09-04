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

class ResourceFactory
{
    protected $database;

    protected $config;

    public function __construct($database, Configuration $config)
    {
        $this->database = $database;
        $this->config = $config;
    }

    public function newSimpleOutput()
    {
        return new SimpleOutput(
            $this->newSerializer(),
            $this->newResource($this->config->getCurrentResourceConfiguration())
        );
    }

    public function newSlimOutput(Response $response)
    {
        return new SlimOutput(
            $response,
            $this->newSerializer(),
            $this->newResource($this->config->getCurrentResourceConfiguration())
        );
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function resourceFor($resourceName)
    {
        $resourceConfig = $this->config->resourceConfigurationFor($resourceName);

        return $this->newResource($resourceConfig);
    }

    protected function newDoctrineRepository(EntityManager $manager, $entityClass)
    {
        return new DoctrineResourceRepository($manager, $entityClass);
    }

    protected function newPdoRepository(PDO $pdo, $entityClass)
    {
        return new PdoResourceRepository($pdo, $entityClass);
    }

    protected function newRepository($entityClass)
    {
        if ($this->database instanceof EntityManager) {
            return $this->newDoctrineRepository($this->database, $entityClass);
        } elseif ($this->database instanceof PDO) {
            return $this->newPdoRepository($this->database, $entityClass);
        }

        throw new \Exception("Couldn't determine database connection type.");
    }

    protected function newSerializer()
    {
        return new JMSSerializer(
            SerializerBuilder::create()->build(),
            new SerializationContext,
            new JMSPropertyExcluder
        );
    }

    protected function newResource(ResourceConfiguration $config)
    {
        $validatorClass = $config->getValidatorClass();

        return new Resource(
            $this->newRepository($config->getEntityClass()),
            new $validatorClass,
            $this->newSecurity(),
            $this
        );
    }

    protected function newSecurity()
    {
        $inspector = new ResourceInspector;

        return new Security(
            new SimpleAuthenticationBouncer($inspector),
            new SimpleRoleBouncer($inspector),
            new SimpleOwnerBouncer($inspector),
            new ParentBouncer($inspector, $this->config->getCurrentResourceConfiguration())
        );
    }
}
