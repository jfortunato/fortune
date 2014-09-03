<?php

namespace Fortune\Resource;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Validator\ResourceValidatorInterface;
use Fortune\Security\SecurityInterface;
use Fortune\Configuration\Configuration;
use Fortune\Resource\Creator\ResourceCreator;

class Resource implements ResourceInterface
{
    protected $repository;

    protected $validator;

    protected $resources;

    protected $security;

    protected $config;

    protected $resourceCreator;

    public function __construct(
        ResourceRepositoryInterface $repository,
        ResourceValidatorInterface $validator,
        SecurityInterface $security,
        Configuration $config,
        ResourceCreator $resourceCreator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->security = $security;
        $this->config = $config;
        $this->resourceCreator = $resourceCreator;
    }

    public function all()
    {
        return $this->repository->findAll();
    }

    public function allByParent($parentId)
    {
        $entityProperty = $this->config->getCurrentResourceConfiguration()->getParentEntityProperty();

        return $this->repository->findBy(array($entityProperty => $parentId));
    }

    public function single($id)
    {
        return $this->repository->find($id);
    }

    public function singleByParent($parentId, $id)
    {
        $entityProperty = $this->config->getCurrentResourceConfiguration()->getParentEntityProperty();

        return $this->repository->findOneBy(array('id' => $id, $entityProperty => $parentId));
    }

    public function create(array $input)
    {
        return $this->repository->create($input);
    }

    public function createWithParent($parentId, array $input)
    {
        $childResourceConfig = $this->config->getCurrentResourceConfiguration();

        $entityProperty = $childResourceConfig->getParentEntityProperty();

        $parentResource = $this->resourceCreator->create($childResourceConfig->getParent());

        $input[$entityProperty] = $parentResource->single($parentId);

        return $this->create($input);
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
