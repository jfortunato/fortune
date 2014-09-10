<?php

namespace Fortune\Repository;

interface ResourceRepositoryInterface
{
    /**
     * Retrieve all objects in the repository.
     *
     * @return array
     */
    public function findAll();

    /**
     * Retrieve an entity by its id.
     *
     * @param int $id
     * @return object
     */
    public function find($id);

    /**
     * Retrieves all entities based on their parent id.
     *
     * @param array $findBy
     * @return array
     */
    public function findByParent($parent_id);

    /**
     * Retrieve an entity based on its parent id.
     *
     * @param array $findBy
     * @return object
     */
    public function findOneByParent($id, $parent_id);

    /**
     * Creates a new entity..
     *
     * @param array $input
     * @return object The newly created object.
     */
    public function create(array $input);

    /**
     * The implementing class decides how relations are handled / added to $input
     *
     * @param array $input
     * @param mixed $parent
     * @return string
     */
    public function createWithParent(array $input, $parent);

    /**
     * Updates an existing entity.
     *
     * @param int $id
     * @param array $input
     * @return object
     */
    public function update($id, array $input);

    /**
     * Deletes an entity based on its id.
     *
     * @param int $id
     * @return void
     */
    public function delete($id);

    /**
     * Get the parent column name or entity field.
     *
     * @return string
     */
    public function getParentRelation();
}
