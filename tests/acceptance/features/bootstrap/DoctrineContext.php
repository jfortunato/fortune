<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Exception\PendingException;
use Fortune\Repository\Driver\DoctrineResourceRepository;

class DoctrineContext extends BaseContext
{
    protected $repository;

    protected $result;

    /**
     * @Given /^The repository is created using the "([^"]*)" resource$/
     */
    public function theRepositoryIsCreatedUsingTheResource($resourceClass)
    {
        $this->repository = new DoctrineResourceRepository($this->manager, $resourceClass);
    }

    /**
     * @When /^I use the "([^"]*)" method with arguments:$/
     */
    public function iUseTheMethodWithArguments($method, TableNode $table)
    {
        $arguments = $table->getRows()[0];

        $this->result = call_user_func_array([$this->repository, $method], $arguments);
    }

    /**
     * @Then /^The result should be an array of "([^"]*)" objects$/
     */
    public function theResultShouldBeAnArrayOfObjects($type)
    {
        assertTrue(is_array($this->result));

        assertInstanceOf($type, $this->result[0]);
    }

    /**
     * @Then /^The result should be a "([^"]*)" object$/
     */
    public function theResultShouldBeAObject($type)
    {
        assertInstanceOf($type, $this->result);
    }

    /**
     * @Given /^The objects should have the following ids:$/
     */
    public function theObjectsShouldHaveTheFollowingIds(TableNode $table)
    {
        $ids = $table->getRows()[0];

        for ($i = 0; $i < count($this->result); $i++) {
            assertEquals($ids[$i], $this->result[$i]->getId());
        }
    }

    /**
     * @When /^I use the "([^"]*)" method with the following input:$/
     */
    public function iUseTheMethodWithTheFollowingInput($method, TableNode $table)
    {
        $input = $table->getHash()[0];

        $this->result = $this->repository->$method($input);
    }

    /**
     * @Then /^I should get an "([^"]*)" exception$/
     */
    public function iShouldGetAnException($exception)
    {
        throw new PendingException();
    }
}
