<?php

use Behat\Behat\Context\BehatContext;
use Doctrine\ORM\Tools\SchemaTool;

abstract class BaseContext extends BehatContext implements DoctrineAwareInterface
{
    protected $manager;

    public function setManager(Doctrine\ORM\EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Clean the database before each scenario
     *
     * @BeforeScenario
     */
    public function beforeScenario($event)
    {
        $metadata = $this->manager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $tool = new SchemaTool($this->manager);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }
}
