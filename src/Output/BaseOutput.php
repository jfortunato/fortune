<?php

namespace Fortune\Output;

use Fortune\Serializer\SerializerInterface;
use Fortune\Resource\ResourceInterface;

abstract class BaseOutput
{
    abstract protected function setJsonHeader();
    abstract protected function setStatusCode($code);
    abstract protected function content($serializedContent);

    protected $serializer;

    protected $resource;

    public function __construct(SerializerInterface $serializer, ResourceInterface $resource)
    {
        $this->serializer = $serializer;
        $this->resource = $resource;
    }

    public function response($content, $code)
    {
        $this->setJsonHeader();

        $this->setStatusCode($code);

        return $this->content($this->serializer->serialize($content));
    }

    protected function responseDenied()
    {
        return $this->response(array('error' => 'Access Denied'), 403);
    }

    protected function responseNotFound()
    {
        return $this->response(array('error' => 'Resource Not Found'), 404);
    }

    protected function responseBadInput()
    {
        return $this->response(array('error' => 'Bad Input'), 400);
    }

    protected function failsSecurity($entity = null)
    {
        return !$this->resource->passesSecurity($entity);
    }

    protected function failsValidation(array $input)
    {
        return !$this->resource->passesValidation($input);
    }

    public function index($parentId = null)
    {
        if ($this->failsSecurity()) {
            return $this->responseDenied();
        }

        $entities = $parentId ? $this->resource->allByParent($parentId):$this->resource->all();

        return $this->response($entities, 200);
    }

    public function show($id, $parentId = null)
    {
        $entity = $parentId ? $this->resource->singleByParent($parentId, $id) : $this->resource->single($id);

        if ($this->failsSecurity($entity)) {
            return $this->responseDenied();
        }

        if (!$entity) {
            return $this->responseNotFound();
        }

        return $this->response($entity, 200);
    }

    public function create(array $input, $parentId = null)
    {
        if ($this->failsSecurity()) {
            return $this->responseDenied();
        }

        if ($this->failsValidation($input)) {
            return $this->responseBadInput();
        }

        $entity = $parentId ? $this->resource->createWithParent($parentId, $input) : $this->resource->create($input);

        return $this->response($entity, 201);
    }

    public function update($id, array $input, $parentId = null)
    {
        $entity = $parentId ? $this->resource->singleByParent($parentId, $id) : $this->resource->single($id);

        if ($this->failsSecurity($entity)) {
            return $this->responseDenied();
        }

        if (!$entity) {
            return $this->responseNotFound();
        }

        if ($this->failsValidation($input)) {
            return $this->responseBadInput();
        }

        $this->resource->update($id, $input);

        return $this->response(null, 204);
    }

    public function delete($id, $parentId = null)
    {
        $entity = $parentId ? $this->resource->singleByParent($parentId, $id) : $this->resource->single($id);

        if ($this->failsSecurity($entity)) {
            return $this->responseDenied();
        }

        if (!$entity) {
            return $this->responseNotFound();
        }

        $this->resource->delete($id);

        return $this->response(null, 204);
    }

    public function getStatusCode()
    {
        return http_response_code();
    }
}
