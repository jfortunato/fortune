<?php

namespace Fortune\Output;

use Fortune\Serializer\SerializerInterface;
use Fortune\ResourceInterface;

/**
 * Base class for doing all the work of taking a resource and turning it into
 * serialized output. Subclasses determine details of how certain responses
 * are set.
 *
 * @package Fortune
 */
abstract class BaseOutput
{
    /**
     * Set an application/json header.
     *
     * @return void
     */
    abstract protected function setJsonHeader();

    /**
     * Sets a status code for the response.
     *
     * @param int $code
     * @return void
     */
    abstract protected function setStatusCode($code);

    /**
     * Set the serialized resource to be output in the response.
     *
     * @param string $serializedContent The resource in its serialized form.
     * @return string
     */
    abstract protected function content($serializedContent);

    /**
     * Get the request input for creating / updating resource.
     *
     * @return array
     */
    abstract protected function getInput();

    /**
     * Does the work of resource serialization.
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * The resource to be serialized.
     *
     * @var Resource
     */
    protected $resource;

    /**
     * Constructor
     *
     * @param SerializerInterface $serializer
     * @param ResourceInterface $resource
     * @return void
     */
    public function __construct(SerializerInterface $serializer, ResourceInterface $resource)
    {
        $this->serializer = $serializer;
        $this->resource = $resource;
    }

    /**
     * Formula for setting header / status code / content.
     *
     * @param mixed $content Any content that will be serialized to output.
     * @param mixed $code The response status code.
     * @return string
     */
    public function response($content, $code)
    {
        $this->setJsonHeader();

        $this->setStatusCode($code);

        return $this->content($this->serializer->serialize($content));
    }

    /**
     * Sets a 403 error response.
     *
     * @return string
     */
    protected function responseDenied()
    {
        return $this->response(array('error' => 'Access Denied'), 403);
    }

    /**
     * Sets a 404 error response.
     *
     * @return string
     */
    protected function responseNotFound()
    {
        return $this->response(array('error' => 'Resource Not Found'), 404);
    }

    /**
     * Sets a 400 error response.
     *
     * @return string
     */
    protected function responseBadInput()
    {
        return $this->response(array('error' => 'Bad Input'), 400);
    }

    /**
     * Checks if the resource is allowed to be accessed by user.
     *
     * @return boolean
     */
    protected function failsSecurity()
    {
        return !$this->resource->passesSecurity();
    }

    /**
     * Checks if $input passes the resource validation.
     *
     * @param array $input
     * @return boolean
     */
    protected function failsValidation(array $input)
    {
        return !$this->resource->passesValidation($input);
    }

    protected function failsParentMatch(array $parents)
    {
        // just verify that the url structure is correct
        // and that we can find parents with the appropriate ids
        // the $parents (ids) go from most senior parent to least senior parent
        // but we need to check for the reverse of this
        $resource = $this->resource->getParentResource();

        foreach (array_reverse($parents) as $parentId) {
            if (!$resource->single($parentId)) {
                return true;
            }

            $resource = $resource->getParentResource();
        }

        return false;
    }

    /**
     * Shows all of a resource.
     *
     * @param int $parentId Required when resource is a sub-resource.
     * @return string
     */
    public function index()
    {
        $parents = func_get_args();

        if ($this->failsSecurity()) {
            return $this->responseDenied();
        }

        if ($this->failsParentMatch($parents)) {
            return $this->responseNotFound();
        }

        $entities = $parents ? $this->resource->allByParent(end($parents)):$this->resource->all();

        return $this->response($entities, 200);
    }

    /**
     * Shows a single resource.
     *
     * @param int $id The id of the resource to find.
     * @param int $parentId Required when resource is a sub-resource.
     * @return string
     */
    public function show()
    {
        $parents = func_get_args();

        $id = array_pop($parents);

        $entity = $parents ? $this->resource->singleByParent(end($parents), $id) : $this->resource->single($id);

        if ($this->failsSecurity()) {
            return $this->responseDenied();
        }

        if (!$entity || $this->failsParentMatch($parents)) {
            return $this->responseNotFound();
        }

        return $this->response($entity, 200);
    }

    /**
     * Creates a new resource.
     *
     * @param int $parentId Required when resource is a sub-resource.
     * @return string
     */
    public function create()
    {
        $parents = func_get_args();

        if ($this->failsSecurity()) {
            return $this->responseDenied();
        }

        if ($this->failsParentMatch($parents)) {
            return $this->responseNotFound();
        }

        $input = $this->getInput();

        if ($this->failsValidation($input)) {
            return $this->responseBadInput();
        }

        $entity = $parents ? $this->resource->createWithParent(end($parents), $input) : $this->resource->create($input);

        return $this->response($entity, 201);
    }

    /**
     * Updates and existing resource.
     *
     * @param int $id The id of the resource to update.
     * @param int $parentId Required when resource is a sub-resource.
     * @return string
     */
    public function update()
    {
        $parents = func_get_args();

        $id = array_pop($parents);

        $entity = $parents ? $this->resource->singleByParent(end($parents), $id) : $this->resource->single($id);

        if ($this->failsSecurity($entity)) {
            return $this->responseDenied();
        }

        if (!$entity || $this->failsParentMatch($parents)) {
            return $this->responseNotFound();
        }

        $input = $this->getInput();

        if ($this->failsValidation($input)) {
            return $this->responseBadInput();
        }

        $this->resource->update($id, $input);

        return $this->response(null, 204);
    }

    /**
     * Deletes an existing resource.
     *
     * @param int $id The id of the resource to delete.
     * @param int $parentId Required when resource is a sub-resource.
     * @return string
     */
    public function delete()
    {
        $parents = func_get_args();

        $id = array_pop($parents);

        $entity = $parents ? $this->resource->singleByParent(end($parents), $id) : $this->resource->single($id);

        if ($this->failsSecurity($entity)) {
            return $this->responseDenied();
        }

        if (!$entity || $this->failsParentMatch($parents)) {
            return $this->responseNotFound();
        }

        $this->resource->delete($id);

        return $this->response(null, 204);
    }

    /**
     * Gets the currently set status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return http_response_code();
    }
}
