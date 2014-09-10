<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client as Guzzle;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;
use Fortune\Configuration\Configuration;

class ResourceContext extends BaseContext
{
    protected $dogs = [];

    protected $client;

    protected $response;

    protected $query = array();

    protected $body = array();

    protected $headers = array();

    protected $configuration;

    protected $resourceConfig;

    protected $parsed;

    public function __construct(array $parameters = array())
    {
        parent::__construct();

        $this->client = new Guzzle([
            'base_url' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => false,
            ],
        ]);
    }

    /**
     * Clean the database before each scenario
     *
     * @BeforeScenario
     */
    public function beforeScenario($event)
    {
        $this->container->dbRecreator->recreateDatabase();
    }

    /**
     * @Given /^The following dogs exist:$/
     */
    public function theFollowingDogsExist(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->thereIsDog($data['id'], array(
                'name' => $data['name'],
            ));
        }
    }

    /**
     * @Given /^The following puppies exist:$/
     */
    public function theFollowingPuppiesExist(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->thereIsPuppy($data['id'], array(
                'name' => $data['name'],
            ));
        }
    }

    /**
     * @When /^I send a (GET|POST|PUT|DELETE) request to "([^"]*)"$/
     */
    public function iSendARequestTo($method, $resource)
    {
        $url = $this->client->getBaseUrl() . $resource;

        $method = strtolower($method);

        $this->response = $this->client->$method($url, [
            'query' => $this->query,
            'body' => $this->body,
            'headers' => $this->headers,
        ]);


        // reset query params in case we make another request
        $this->query = array();
        $this->body = array();
        $this->headers = array();
    }

    /**
     * @Given /^I send the following parameters:$/
     */
    public function iSendTheFollowingParameters(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            foreach ($data as $key => $value) {
                $this->addRequestParameter($key, $value);
            }
        }
    }

    /**
     * @Given /^I send the ([a-zA-Z_]+) "([^"]*)"$/
     * @Given /^I send the ([a-zA-Z_]+):$/
     */
    public function addRequestParameter($parameter, $value)
    {
        // the parameter we are adding could be an array of params
        if ($value instanceof TableNode) {
            $value = $value->getHash();
        }

        $this->body[$parameter] = $value;
    }

    /**
     * @Then /^The response code should be (\d+)$/
     */
    public function theResponseCodeShouldBe($code)
    {
        assertEquals($code, $this->response->getStatusCode());
    }

    /**
     * @Then /^The response code should not be (\d+)$/
     */
    public function theResponseCodeShouldNotBe($code)
    {
        assertNotEquals($code, $this->response->getStatusCode());
    }

    /**
     * @Then /^The response is JSON$/
     */
    public function theResponseIsJson()
    {
        assertEquals('application/json', $this->response->getHeader('Content-Type'));
        assertJson((string) $this->response->getBody());
    }

    /**
     * @Given /^The response should contain JSON '([^']*)'$/
     */
    public function theResponseShouldContainJson($json)
    {
        $this->assertArrayContainsArray(json_decode($json, true), $this->response->json());
    }

    /**
     * @Then /^The response should not contain key "([^"]*)"$/
     */
    public function theResponseShouldNotContainKey($key)
    {
        assertArrayNotHasKey($key, $this->response->json());
    }

    protected function assertArrayContainsArray(array $needle, array $haystack)
    {
        foreach ($needle as $key => $value) {
            assertArrayHasKey($key, $haystack);
        }

        if (is_array($value)) {
            $this->assertArrayContainsArray($value, $haystack[$key]);
        } else {
            assertEquals($value, $haystack[$key]);
        }
    }

    public function thereIsDog($id = null, array $dogExtra = array())
    {
        if (null === $dog = $this->getManager()->getRepository('Fortune\Test\Entity\Dog')->findOneBy(array('id' => $id))) {
            $dog = new Fortune\Test\Entity\Dog;
            $dog->setName($dogExtra['name']);

            $this->getManager()->persist($dog);
            $this->getManager()->flush();
        }

        return $dog;
    }

    public function thereIsPuppy($id = null, array $puppyExtra = array())
    {
        if (null === $puppy = $this->getManager()->getRepository('Fortune\Test\Entity\Puppy')->findOneBy(array('id' => $id))) {
            $puppy = new Fortune\Test\Entity\Puppy;
            $puppy->setName($puppyExtra['name']);

            $this->getManager()->persist($puppy);
            $this->getManager()->flush();
        }

        return $puppy;
    }

    /**
     * @Given /^I am not logged in$/
     */
    public function iAmNotLoggedIn()
    {
        assertFalse(isset($_SESSION['username']));
    }

    /**
     * @Given /^I am logged in$/
     */
    public function iAmLoggedIn()
    {
        $this->query['doLogin'] = true;
    }

    /**
     * @Given /^The resource "([^"]*)" requires authentication$/
     */
    public function theResourceRequiresAuthentication($resource)
    {
        $parsed = Yaml::parse(file_get_contents(__DIR__ . '/fixtures/config.yaml'));

        $parsed[$resource]['access_control']['authentication'] = true;

        $dumper = new Dumper;

        file_put_contents(__DIR__ . '/fixtures/config.yaml', $dumper->dump($parsed, 3));
    }

    /**
     * @Given /^I have the role "([^"]*)"$/
     */
    public function iHaveTheRole($role)
    {
        $this->query['haveRole'] = $role;
    }

    /**
     * @Given /^I dont have a role$/
     */
    public function iDontHaveARole()
    {
        assertFalse(isset($_SESSION['role']));
    }

    /**
     * @Given /^The resource "([^"]*)" requires role "([^"]*)"$/
     */
    public function theResourceRequiresRole($resource, $role)
    {
        $parsed = Yaml::parse(file_get_contents(__DIR__ . '/fixtures/config.yaml'));

        $parsed[$resource]['access_control']['role'] = $role;

        $dumper = new Dumper;

        file_put_contents(__DIR__ . '/fixtures/config.yaml', $dumper->dump($parsed, 3));
    }

    /**
     * @Given /^The resource "([^"]*)" requires owner for access$/
     */
    public function theResourceRequiresOwnerForAccess($resource)
    {
        $parsed = Yaml::parse(file_get_contents(__DIR__ . '/fixtures/config.yaml'));

        $parsed[$resource]['access_control']['owner'] = true;

        $dumper = new Dumper;

        file_put_contents(__DIR__ . '/fixtures/config.yaml', $dumper->dump($parsed, 3));

    }

    /**
     * @Given /^The error message should contain "([^"]*)"$/
     */
    public function theErrorMessageShouldContain($text)
    {
        assertArrayHasKey('error', $this->response->json());

        assertNotSame(false, strpos($this->response->json()['error'], $text));
    }

    /**
     * @Given /^I am the owner of the resource$/
     */
    public function iAmTheOwnerOfTheResource()
    {
        // the SimpleOwnerBouncer just checks if $_SESSION['username'] isset
        // to determine the owner, so just make sure we have set login
        assertTrue($this->query['doLogin']);
    }

    /**
     * @Given /^The puppy "([^"]*)" belongs to dog "([^"]*)"$/
     */
    public function thePuppyBelongsToDog($puppyName, $dogName)
    {
        $puppy = $this->getManager()->getRepository('Fortune\Test\Entity\Puppy')->findOneBy(array('name' => $puppyName));
        $dog = $this->getManager()->getRepository('Fortune\Test\Entity\Dog')->findOneBy(array('name' => $dogName));

        $puppy->setDog($dog);

        $this->getManager()->persist($puppy);
        $this->getManager()->flush();
    }

    /**
     * @Given /^The resource "([^"]*)" belongs to resource "([^"]*)"$/
     */
    public function theResourceBelongsToResource($child, $parent)
    {
        //throw new PendingException();
    }

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
