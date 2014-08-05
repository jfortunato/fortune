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
        $this->fillAttributes($resource, $input);

        $this->manager->persist($resource);
        $this->manager->flush();

        return $resource;
    }

    public function update($id, array $input)
    {
        $resource = $this->find($id);
        $this->fillAttributes($resource, $input);

        $this->manager->persist($resource);
        $this->manager->flush();

        return $resource;
    }

    public function delete($id)
    {
        $resource = $this->find($id);

        $this->manager->remove($resource);
        $this->manager->flush();
    }

    protected function fillAttributes($resource, $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $setter = "set" . ucfirst($attribute);

            if (method_exists($resource, $setter)) {
                $resource->$setter($value);
            }
        }
    }
}
