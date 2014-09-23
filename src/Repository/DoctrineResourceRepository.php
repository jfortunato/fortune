<?php

namespace Fortune\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Query;

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
        return $this->manager->createQuery("SELECT a FROM {$this->resourceClass} a")->getArrayResult();
    }

    /**
     * @Override
     */
    public function find($id, $hydration = Query::HYDRATE_ARRAY)
    {
        return $this->manager->getRepository($this->resourceClass)->createQueryBuilder('a')
            ->where("a.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($hydration)
            ;
    }

    /**
     * @Override
     */
    public function findByParent($parent_id)
    {
        return $this->manager->getRepository($this->resourceClass)->createQueryBuilder('a')
            ->where("IDENTITY(a.{$this->getParentRelation()}) = :parent")
            ->setParameter('parent', $parent_id)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    /**
     * @Override
     */
    public function findOneByParent($id, $parent_id)
    {
        $result = $this->manager->getRepository($this->resourceClass)->createQueryBuilder('a')
            ->where("a.id = :id")
            ->andWhere("IDENTITY(a.{$this->getParentRelation()}) = :parent")
            ->setParameter('id', $id)
            ->setParameter('parent', $parent_id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY)
            ;

        // add the relation to output, for use when validating updated resource
        if ($result) {
            $result[$this->getParentRelation()] = $parent_id;

            return $result;
        }

        return null;
    }

    /**
     * @Override
     */
    public function findForRelation($parent_id)
    {
        return $this->find($parent_id, Query::HYDRATE_OBJECT);
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

        return $this->find($resource->getId());
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
        $resource = $this->find($id, Query::HYDRATE_OBJECT);
        $this->fillAttributes($resource, $input);

        $this->manager->persist($resource);
        $this->manager->flush();

        return $this->find($id);
    }

    /**
     * @Override
     */
    public function delete($id)
    {
        $resource = $this->find($id, Query::HYDRATE_OBJECT);

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
            ? $this->parent : Inflector::singularize($this->parent);
    }
}
