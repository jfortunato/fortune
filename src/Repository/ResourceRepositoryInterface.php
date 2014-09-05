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
     * Retrieves all entities based on criteria.
     *
     * @param array $findBy
     * @return array
     */
    public function findBy(array $findBy);

    /**
     * Retrieve an entity based on criteria.
     *
     * @param array $findBy
     * @return object
     */
    public function findOneBy(array $findBy);

    /**
     * Creates a new entity..
     *
     * @param array $input
     * @return object The newly created object.
     */
    public function create(array $input);

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
     * Get the entity class.
     *
     * @return string
     */
    public function getClassName();
}
