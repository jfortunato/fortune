<?php

namespace Fortune\Repository\Driver;

use Doctrine\ORM\EntityManager;
use Fortune\Repository\ResourceRepositoryInterface;

class DoctrineResourceRepository implements ResourceRepositoryInterface
{
    protected $manager;

    protected $resourceClass;

    public function __construct(EntityManager $manager, $resourceClass)
    {
        $this->manager = $manager;
        $this->resourceClass = $resourceClass;
    }

    public function findAll()
    {
        return $this->manager->getRepository($this->resourceClass)->findAll();
    }

    public function find($id)
    {
        return $this->manager->getRepository($this->resourceClass)->find($id);
    }

    public function create(array $input)
    {
        $resource = new $this->resourceClass;
        $resource->setName($input['name']);

        $this->manager->persist($resource);
        $this->manager->flush();

        return $resource;
    }
}
