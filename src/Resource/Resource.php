<?php

namespace Fortune\Resource;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Validator\ResourceValidatorInterface;
use Fortune\Security\SecurityInterface;

class Resource implements ResourceInterface
{
    protected $repository;

    protected $validator;

    protected $resources;

    protected $security;

    public function __construct(ResourceRepositoryInterface $repository, ResourceValidatorInterface $validator, SecurityInterface $security)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->security = $security;
    }

    public function all()
    {
        return $this->repository->findAll();
    }

    public function single($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $input)
    {
        return $this->repository->create($input);
    }

    public function update($id, array $input)
    {
        return $this->repository->update($id, $input);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function passesSecurity($entity = null)
    {
        return $this->security->isAllowed($entity ?: $this->repository->getClassName());
    }

    public function passesValidation(array $input)
    {
        return $this->validator->validate($input);
    }
}
