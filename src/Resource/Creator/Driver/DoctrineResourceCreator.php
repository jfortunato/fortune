<?php

namespace Fortune\Resource\Creator\Driver;

use Doctrine\ORM\EntityManager;
use Fortune\Configuration\Configuration;
use Fortune\Security\SecurityInterface;
use Fortune\Repository\Driver\DoctrineResourceRepository;
use Fortune\Resource\Creator\ResourceCreator;

class DoctrineResourceCreator extends ResourceCreator
{
    protected $manager;

    public function __construct(Configuration $config, SecurityInterface $security, EntityManager $manager)
    {
        $this->manager = $manager;

        parent::__construct($config, $security);
    }

    protected function createResourceRepository($entityClass)
    {
        return new DoctrineResourceRepository($this->manager, $entityClass);
    }
}
