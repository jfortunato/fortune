<?php

namespace Fortune\Repository;

use Doctrine\ORM\EntityManager;
use Fortune\Repository\ResourceRepositoryInterface;

/**
 * Doctrine implementaion for repository to find objects in database.
 *
 * @package Fortune
 */
class DoctrineResourceRepository implements ResourceRepositoryInterface
{
    /**
     * The doctrine EntityManager object
     *
     * @var EntityManager
     */
    protected $manager;

    /**
     * The entity class name.
     *
     * @var string
     */
    protected $resourceClass;

    /**
     * Constructor
     *
     * @param EntityManager $manager
     * @param mixed $resourceClass
     * @return void
     */
    public function __construct(EntityManager $manager, $resourceClass)
    {
        $this->manager = $manager;
        $this->resourceClass = $resourceClass;
    }

    /**
     * @Override
     */
    public function findAll()
    {
        return $this->manager->getRepository($this->resourceClass)->findAll();
    }

    /**
     * @Override
     */
    public function find($id)
    {
        return $this->manager->getRepository($this->resourceClass)->find($id);
    }

    /**
     * @Override
     */
    public function findBy(array $findBy)
    {
        return $this->manager->getRepository($this->resourceClass)->findBy($findBy);
    }

    /**
     * @Override
     */
    public function findOneBy(array $findBy)
    {
        return $this->manager->getRepository($this->resourceClass)->findOneBy($findBy);
    }

    /**
     * @Override
     */
    public function create(array $input)
    {
        $resource = new $this->resourceClass;
        $this->fillAttributes($resource, $input);

        $this->manager->persist($resource);
        $this->manager->flush();

        return $resource;
    }

    /**
     * @Override
     */
    public function update($id, array $input)
    {
        $resource = $this->find($id);
        $this->fillAttributes($resource, $input);

        $this->manager->persist($resource);
        $this->manager->flush();

        return $resource;
    }

    /**
     * @Override
     */
    public function delete($id)
    {
        $resource = $this->find($id);

        $this->manager->remove($resource);
        $this->manager->flush();
    }

    /**
     * @Override
     */
    public function getClassName()
    {
        return $this->resourceClass;
    }

    /**
     * Sets all the properties on the entity.
     *
     * @param mixed $resource
     * @param array $attributes
     * @return void
     */
    protected function fillAttributes($resource, array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $setter = "set" . ucfirst($attribute);

            if (method_exists($resource, $setter)) {
                $resource->$setter($value);
            }
        }
    }
}
