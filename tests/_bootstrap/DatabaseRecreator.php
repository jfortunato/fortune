<?php

namespace Fortune\Test;

use Doctrine\ORM\EntityManager;

class DatabaseRecreator
{
    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function recreateDatabase()
    {
        $metadata = $this->manager->getMetadataFactory()->getAllMetadata();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->manager);
        $tool->dropSchema($metadata);
        $tool->createSchema($metadata);
    }
}
