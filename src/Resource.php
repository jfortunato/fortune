<?php

namespace Fortune;

use Fortune\Repository\ResourceRepositoryInterface;
use Fortune\Validator\ResourceValidatorInterface;
use Fortune\Security\SecurityInterface;
use Fortune\Configuration\ResourceConfiguration;

/**
 * A Resource is basically a repository with security and validation.
 *
 * @package Fortune
 */
class Resource implements ResourceInterface
{
    /**
     * The repository to fetch resources from.
     *
     * @var ResourceRepositoryInterface
     */
    protected $repository;

    /**
     * Determines resource input validation.
     *
     * @var ResourceValidatorInterface
     */
    protected $validator;

    /**
     * Determines resource accessibility.
     *
     * @var SecurityInterface
     */
    protected $security;

    /**
     * Used to create new resources.
     *
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /**
     * The user provided configuration for this resource.
     *
     * @var ResourceConfiguration
     */
    protected $config;

    /**
     * Constructor
     *
     * @param ResourceRepositoryInterface $repository
     * @param ResourceValidatorInterface $validator
     * @param SecurityInterface $security
     * @param ResourceFactory $resourceFactory
     * @param ResourceConfiguration $config
     * @return void
     */
    public function __construct(
        ResourceRepositoryInterface $repository,
        ResourceValidatorInterface $validator,
        SecurityInterface $security,
        ResourceFactory $resourceFactory,
        ResourceConfiguration $config
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->security = $security;
        $this->resourceFactory = $resourceFactory;
        $this->config = $config;
    }

    /**
     * @Override
     */
    public function all()
    {
        return $this->repository->findAll();
    }

    /**
     * @Override
     */
    public function allByParent($parentId)
    {
        return $this->repository->findByParent($parentId);
    }

    /**
     * @Override
     */
    public function single($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @Override
     */
    public function singleByParent($parentId, $id)
    {
        return $this->repository->findOneByParent($id, $parentId);
    }

    /**
     * @Override
     */
    public function create(array $input)
    {
        return $this->repository->create($input);
    }

    /**
     * @Override
     */
    public function createWithParent($parentId, array $input)
    {
        $childResourceConfig = $this->resourceFactory->getConfig()
            ->getCurrentResourceConfiguration();

        $parentResource = $this->resourceFactory->resourceFor($childResourceConfig->getParent());

        $parent = $parentResource->single($parentId);

        return $this->repository->createWithParent($input, $parent);
    }

    /**
     * @Override
     */
    public function update($id, array $input)
    {
        return $this->repository->update($id, $input);
    }

    /**
     * @Override
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * @Override
     */
    public function passesSecurity()
    {
        return $this->security->isAllowed();
    }

    /**
     * @Override
     */
    public function passesValidation(array $input)
    {
        return $this->validator->validate($input);
    }

    /**
     * @Override
     */
    public function getParentResource()
    {
        return $this->config->getParent() ?
            $this->resourceFactory->resourceFor($this->config->getParent()) : null;
    }
}
