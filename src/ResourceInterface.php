<?php

namespace Fortune;

interface ResourceInterface
{
    public function all();
    public function allByParent($parentId);
    public function single($id);
    public function singleByParent($parentId, $id);
    public function create(array $input);
    public function createWithParent($parentId, array $input);
    public function update($id, array $input);
    public function delete($id);
    public function passesSecurity($entity = null);
    public function passesValidation(array $input);
}
