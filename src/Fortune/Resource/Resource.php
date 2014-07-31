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

    protected $resources;

    public function __construct(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->output = $output;
    }

    public function index()
    {
        $this->resources = $this->repository->findAll();

        return $this->response(200);
    }

    public function show($id)
    {
        $this->resources = $this->repository->find($id);

        return $this->response(200);
    }

    public function create(array $input)
    {
        $this->resources = $this->repository->create($input);

        return $this->response(201);
    }

    public function update($id, array $input)
    {
        $this->repository->update($id, $input);

        return $this->response(204);
    }

    protected function response($code)
    {
        return $this->output->response($this->serialize($this->resources), $code);
    }

    protected function serialize($resources)
    {
        return $this->serializer->serialize($resources);
    }
}
