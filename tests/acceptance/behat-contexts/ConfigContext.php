<?php

use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Exception\PendingException;
use Symfony\Component\Yaml\Yaml;
use Fortune\Configuration\Configuration;

class ConfigContext extends BaseContext
{
    protected $configuration;

    protected $resourceConfig;

    protected $parsed;

    /**
     * @Given /^There is a config file containing the following:$/
     */
    public function thereIsAConfigFileContainingTheFollowing(PyStringNode $string)
    {
        file_put_contents(__DIR__ . '/fixtures/config.yaml', $string);

        if (!file_exists(__DIR__ . '/fixtures/config.yaml')) {
            throw new Exception("The config.yaml file doesn't exist.");
        }
    }

    /**
     * @When /^I parse the config file$/
     */
    public function iParseTheConfigFile()
    {
        $this->parsed = Yaml::parse(file_get_contents(__DIR__ . '/fixtures/config.yaml'));
    }

    /**
     * @Given /^I send the parsed config to a Configuration object$/
     */
    public function iSendTheParsedConfigToAConfigurationObject()
    {
        $this->configuration = new Configuration($this->parsed);
    }

    /**
     * @Given /^The Configuration should contain the "([^"]*)" ResourceConfiguration$/
     */
    public function theConfigurationShouldContainTheResourceconfiguration($resourceName)
    {
        $this->resourceConfig = $this->configuration->resourceConfigurationFor($resourceName);

        assertInstanceOf('Fortune\Configuration\ResourceConfiguration', $this->resourceConfig);
    }

    /**
     * @Given /^The ResourceConfiguration\'s "([^"]*)" should be "([^"]*)"$/
     */
    public function theResourceconfigurationsShouldBe($getter, $value)
    {
        $method = "get{$getter}";

        assertSame($value, $this->resourceConfig->$method());
    }
}
