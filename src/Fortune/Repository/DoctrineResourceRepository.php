<?php

namespace Fortune\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

class DoctrineResourceRepository implements ResourceRepositoryInterface
{
    protected $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }


    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }
}
