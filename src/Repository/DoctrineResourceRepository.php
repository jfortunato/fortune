<?php

namespace Fortune\Repository;

use Doctrine\ORM\EntityManager;

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
     * If the resource has a related parent, this is the parents resource name.
     *
     * @var string|null
     */
    protected $parent;

    /**
     * Constructor
     *
     * @param EntityManager $manager
     * @param mixed $resourceClass
     * @return void
     */
    public function __construct(EntityManager $manager, $resourceClass, $parent = null)
    {
        $this->manager = $manager;
        $this->resourceClass = $resourceClass;
        $this->parent = $parent;
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
    public function findByParent($parent_id)
    {
        $findBy = array($this->getParentRelation() => $parent_id);

        return $this->manager->getRepository($this->resourceClass)->findBy($findBy);
    }

    /**
     * @Override
     */
    public function findOneByParent($id, $parent_id)
    {
        $findBy = array('id' => $id, $this->getParentRelation() => $parent_id);

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
    public function createWithParent(array $input, $parent)
    {
        $input[$this->getParentRelation()] = $parent;

        return $this->create($input);
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

    /**
     * @Override
     *
     * Reads the entity class for its parents relation attr name.
     */
    public function getParentRelation()
    {
        // the parent resource may be pluralized
        // but the property name is most likely singular
        // if property name actually plural just use that
        // otherwise make it singular
        $reflection = new \ReflectionClass($this->resourceClass);

        return $reflection->hasProperty($this->parent)
            ? $this->parent : preg_replace('/s$/', '', $this->parent);
    }
}
