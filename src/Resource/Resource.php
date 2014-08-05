<?php

namespace Fortune\Resource;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;
use Fortune\Output\OutputInterface;
use Fortune\Validator\ResourceValidatorInterface;

class Resource
{
    protected $repository;

    protected $serializer;

    protected $output;

    protected $validator;

    protected $resources;

    public function __construct(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output, ResourceValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->output = $output;
        $this->validator = $validator;
    }

    public function index()
    {
        $this->resources = $this->repository->findAll();

        return $this->response(200);
    }

    public function show($id)
    {
        $this->resources = $this->repository->find($id);

        $code = $this->resources ? 200:404;

        return $this->response($code);
    }

    public function create(array $input)
    {
        if (!$this->validator->validate($input)) {
            return $this->response(400);
        }

        $this->resources = $this->repository->create($input);

        return $this->response(201);
    }

    public function update($id, array $input)
    {
        if (!$this->validator->validate($input)) {
            return $this->response(400);
        }

        $this->repository->update($id, $input);

        return $this->response(204);
    }

    public function delete($id)
    {
        $this->repository->delete($id);

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
