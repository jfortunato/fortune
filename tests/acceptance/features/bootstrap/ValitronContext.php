<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Exception\PendingException;
use Valitron\Validator;
use Fortune\Test\Validator\TestValitronResourceValidator;

class ValitronContext extends BaseContext
{
    protected $input;

    protected $validator;

    protected $validationResult;

    /**
     * @Given /^I have extended the ValitronResourceValidator$/
     */
    public function iHaveExtendedTheValitronresourcevalidator()
    {
        $this->validator = new TestValitronResourceValidator;
    }

    /**
     * @Given /^I have the following input:$/
     */
    public function iHaveTheFollowingInput(TableNode $table)
    {
        $this->input = $table->getHash()[0];
    }

    /**
     * @Given /^The input "([^"]*)" is required$/
     */
    public function theInputIsRequired($requiredInput)
    {
        $this->validator->addRule('required', $requiredInput);
    }

    /**
     * @When /^I validate the input$/
     */
    public function iValidateTheInput()
    {
        $this->validationResult = $this->validator->validate($this->input);
    }

    /**
     * @Then /^The input should be valid$/
     */
    public function theInputShouldBeValid()
    {
        assertTrue($this->validationResult);

        // reset
        $this->validationResult = false;
    }

    /**
     * @Then /^The input should be invalid$/
     */
    public function theInputShouldBeInvalid()
    {
        assertFalse($this->validationResult);
    }
}
