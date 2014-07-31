<?php

namespace Fortune\Repository;

interface ResourceRepositoryInterface
{
    public function findAll();
    public function find($id);
}
