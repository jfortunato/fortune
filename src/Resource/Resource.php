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
            return $this->responseDenied();
        }

        $this->resources = $this->repository->findAll();

        return $this->response(200);
    }

    public function show($id)
    {
        $this->resources = $this->repository->find($id);

        if (!$this->security->isAllowed($this->resources ?: $this->repository->getClassName())) {
            return $this->responseDenied();
        }

        return $this->resources ? $this->response(200):$this->response(404, array('error' => 'Resource Not Found'));
    }

    public function create(array $input)
    {
        if (!$this->security->isAllowed($this->repository->getClassName())) {
            return $this->responseDenied();
        }

        if (!$this->validator->validate($input)) {
            return $this->response(400, array('error' => 'Bad Input'));
        }

        $this->resources = $this->repository->create($input);

        return $this->response(201);
    }

    public function update($id, array $input)
    {
        $resource = $this->repository->find($id);

        if (!$this->security->isAllowed($resource ?: $this->repository->getClassName())) {
            return $this->responseDenied();
        }

        if (!$resource) {
            return $this->response(404, array('error' => 'Resource Not Found'));
        }

        if (!$this->validator->validate($input)) {
            return $this->response(400, array('error' => 'Bad Input'));
        }

        $this->repository->update($id, $input);

        return $this->response(204);
    }

    public function delete($id)
    {
        $resource = $this->repository->find($id);

        if (!$this->security->isAllowed($resource ?: $this->repository->getClassName())) {
            return $this->responseDenied();
        }

        $this->repository->delete($id);

        return $this->response(204);
    }

    protected function response($code, $content = null)
    {
        return $this->output->response($this->serialize($content ?: $this->resources), $code);
    }

    protected function responseDenied()
    {
        return $this->response(403, array('error' => 'Access Denied'));
    }

    protected function serialize($resources)
    {
        return $this->serializer->serialize($resources);
    }
}
