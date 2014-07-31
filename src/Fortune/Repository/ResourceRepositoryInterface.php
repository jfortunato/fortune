<?php

namespace Fortune\Repository;

interface ResourceRepositoryInterface
{
    public function findAll();
    public function find($id);
    public function create(array $input);
    public function update($id, array $input);
}
