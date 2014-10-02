<?php

namespace Fortune;

interface ResourceInterface
{
    /**
     * Finds all of a resource
     *
     * @return array
     */
    public function all();

    /**
     * Finds all of a resource based on its parent relation.
     *
     * @param int $parentId
     * @return array
     */
    public function allByParent($parentId);

    /**
     * Finds a single resource.
     *
     * @param int $id
     * @return object
     */
    public function single($id);

    /**
     * Finds a single resource based on its parent relation.
     *
     * @param int $parentId
     * @param int $id
     * @return object
     */
    public function singleByParent($parentId, $id);

    /**
     * Finds whatever the underlying repository needs
     * to make a relation.
     *
     * @param int $parentId
     * @return mixed
     */
    public function singleForRelation($parentId);

    /**
     * Creates a new resource.
     *
     * @param array $input
     * @return object
     */
    public function create(array $input);

    /**
     * Creates a new resource with a parent relation.
     *
     * @param int $parentId
     * @param int $input
     * @return object
     */
    public function createWithParent($parentId, array $input);

    /**
     * Updates an existing resource with $input.
     *
     * @param int $id
     * @param array $input
     * @return object
     */
    public function update($id, array $input);

    /**
     * Deletes a resource.
     *
     * @param int $id
     * @return void
     */
    public function delete($id);

    /**
     * Check if user passes resource security.
     *
     * @return boolean
     */
    public function passesSecurity($method, $identifiers = null);

    /**
     * Check if $input passes resource validation.
     *
     * @param array $input
     * @return boolean
     */
    public function passesValidation(array $input);

    /**
     * Gets the parent for a resource if one exists.
     *
     * @return Resource|null
     */
    public function getParentResource();
}
