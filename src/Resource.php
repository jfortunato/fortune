<?php

namespace Fortune;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Validator\ResourceValidatorInterface;
use Fortune\Security\SecurityInterface;

class Resource implements ResourceInterface
{
    protected $repository;

    protected $validator;

    protected $security;

    protected $resourceFactory;

    public function __construct(
        ResourceRepositoryInterface $repository,
        ResourceValidatorInterface $validator,
        SecurityInterface $security,
        ResourceFactory $resourceFactory
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->security = $security;
        $this->resourceFactory = $resourceFactory;
    }

    public function all()
    {
        return $this->repository->findAll();
    }

    public function allByParent($parentId)
    {
        $entityProperty = $this->resourceFactory->getConfig()
            ->getCurrentResourceConfiguration()->getParentEntityProperty();

        return $this->repository->findBy(array($entityProperty => $parentId));
    }

    public function single($id)
    {
        return $this->repository->find($id);
    }

    public function singleByParent($parentId, $id)
    {
        $entityProperty = $this->resourceFactory->getConfig()
            ->getCurrentResourceConfiguration()->getParentEntityProperty();

        return $this->repository->findOneBy(array('id' => $id, $entityProperty => $parentId));
    }

    public function create(array $input)
    {
        return $this->repository->create($input);
    }

    public function createWithParent($parentId, array $input)
    {
        $childResourceConfig = $this->resourceFactory->getConfig()
            ->getCurrentResourceConfiguration();

        $entityProperty = $childResourceConfig->getParentEntityProperty();

        $parentResource = $this->resourceFactory->resourceFor($childResourceConfig->getParent());

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
