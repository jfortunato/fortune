<?php

namespace Fortune\Repository;

use \PDO;
use Doctrine\Common\Inflector\Inflector;

/**
 * PDO implementaion for repository to find resources in database.
 *
 * @package Fortune
 */
class PdoResourceRepository implements ResourceRepositoryInterface
{
    /**
     * The handle to the PDO object.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * The name of the table to locate this resource.
     *
     * @var string
     */
    protected $tableName;

    /**
     * The name of this resources parent resource if applicable.
     *
     * @var string
     */
    protected $parent;

    /**
     * Constructor
     *
     * @param PDO $pdo
     * @param string $tableName
     * @param string $parent
     * @return void
     */
    public function __construct(PDO $pdo, $tableName, $parent = null)
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->parent = $parent;
    }

    /**
     * @Override
     */
    public function findAll()
    {
        $sth = $this->pdo->prepare("SELECT * FROM {$this->tableName}");
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @Override
     */
    public function find($id)
    {
        $sth = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id = :id");
        $sth->bindParam('id', $id);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @Override
     */
    public function findByParent($parent_id)
    {
        $sth = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE {$this->getParentRelation()} = :parent_id");
        $sth->bindParam('parent_id', $parent_id);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @Override
     */
    public function findOneByParent($id, $parent_id)
    {
        $relation = $this->getParentRelation();

        $sth = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id = :id AND {$relation} = :parent_id");
        $sth->bindParam('id', $id);
        $sth->bindParam('parent_id', $parent_id);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @Override
     */
    public function findForRelation($parent_id)
    {
        return $this->find($parent_id);
    }

    /**
     * @Override
     */
    public function create(array $input)
    {
        $params = array();

        foreach (array_keys($input) as $param) {
            $params[$param] = ':' . $param;
        }

        $cols = implode(', ', array_keys($params));
        $values = implode(', ', $params);

        $sth = $this->pdo->prepare("INSERT INTO {$this->tableName} ({$cols}) VALUES ({$values})");
        $sth->execute($input);

        return $this->find($this->pdo->lastInsertId());
    }

    /**
     * @Override
     */
    public function createWithParent(array $input, $parent)
    {
        $input[$this->getParentRelation()] = $parent['id'];

        return $this->create($input);
    }
    
    /**
     * @Override
     */
    public function update($id, array $input)
    {
        $params = array();

        foreach (array_keys($input) as $param) {
            $params[] = $param . " = :" . $param;
        }

        $updates = implode(', ', $params);

        $sth = $this->pdo->prepare("UPDATE {$this->tableName} SET {$updates} WHERE id = :id");
        // add the id to the input
        $input['id'] = $id;
        $sth->execute($input);

        return $this->find($id);

    }

    /**
     * @Override
     */
    public function delete($id)
    {
        $sth = $this->pdo->prepare("DELETE FROM {$this->tableName} WHERE id = :id");
        $sth->bindParam('id', $id);
        $sth->execute();
    }

    /**
     * @Override
     *
     * Adds _id to the end of the singularized version of this resources parent.
     */
    public function getParentRelation()
    {
        return Inflector::singularize($this->parent) . '_id';
    }
}
