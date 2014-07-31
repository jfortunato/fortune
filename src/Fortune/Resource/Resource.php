<?php

namespace Fortune\Resource;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;
use Fortune\Output\OutputInterface;

class Resource
{
    protected $repository;

    protected $serializer;

    protected $output;

    public function __construct(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->output = $output;
    }

    public function index()
    {
        $resources = $this->repository->findAll();

        return $this->output->response($this->serialize($resources), 200);
    }

    public function show($id)
    {
        $resource = $this->repository->find($id);

        return $this->output->response($this->serialize($resource), 200);
    }

    public function create(array $input)
    {
        $resource = $this->repository->create($input);

        return $this->output->response($this->serialize($resource), 201);
    }

    protected function serialize($resources)
    {
        return $this->serializer->serialize($resources);
    }
}
