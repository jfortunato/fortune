<?php

namespace Fortune\Resource;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Serializer\SerializerInterface;
use Fortune\Output\OutputInterface;
use Fortune\Validator\ResourceValidatorInterface;
use Fortune\Security\SecurityInterface;

class Resource
{
    protected $repository;

    protected $serializer;

    protected $output;

    protected $validator;

    protected $resources;

    protected $security;

    public function __construct(ResourceRepositoryInterface $repository, SerializerInterface $serializer, OutputInterface $output, ResourceValidatorInterface $validator, SecurityInterface $security)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->output = $output;
        $this->validator = $validator;
        $this->security = $security;
    }

    public function index()
    {
        if (!$this->security->isAllowed($this->repository->getClassName())) {
            return $this->response(403);
        }

        $this->resources = $this->repository->findAll();

        return $this->response(200);
    }

    public function show($id)
    {
        if (!$this->security->isAllowed($this->repository->getClassName())) {
            return $this->response(403);
        }

        $this->resources = $this->repository->find($id);

        $code = $this->resources ? 200:404;

        return $this->response($code);
    }

    public function create(array $input)
    {
        if (!$this->security->isAllowed($this->repository->getClassName())) {
            return $this->response(403);
        }

        if (!$this->validator->validate($input)) {
            return $this->response(400);
        }

        $this->resources = $this->repository->create($input);

        return $this->response(201);
    }

    public function update($id, array $input)
    {
        if (!$this->security->isAllowed($this->repository->getClassName())) {
            return $this->response(403);
        }

        if (!$this->repository->find($id)) {
            return $this->response(404);
        }

        if (!$this->validator->validate($input)) {
            return $this->response(400);
        }

        $this->repository->update($id, $input);

        return $this->response(204);
    }

    public function delete($id)
    {
        if (!$this->security->isAllowed($this->repository->getClassName())) {
            return $this->response(403);
        }

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
