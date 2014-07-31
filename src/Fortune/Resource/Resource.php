<?php

namespace Fortune\Resource;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;

class Resource
{
    protected $repository;

    protected $serializer;

    public function __construct(ResourceRepositoryInterface $repository, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    public function index()
    {
        $resources = $this->repository->findAll();

        return $this->serializer->serialize($resources);
    }

    public function show($id)
    {
        $resource = $this->repository->find($id);

        return $this->serializer->serialize($resource);
    }
}
