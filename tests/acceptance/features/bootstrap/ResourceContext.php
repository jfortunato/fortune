<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client as Guzzle;

class ResourceContext extends BaseContext
{
    protected $dogs = [];

    protected $client;

    protected $response;

    protected $query = array();

    protected $headers = array();

    public function __construct(array $parameters = array())
    {
        $this->client = new Guzzle([
            'base_url' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => false,
            ],
        ]);
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
     * @When /^I send a (GET|POST|PUT|DELETE) request to "([^"]*)"$/
     */
    public function iSendARequestTo($method, $resource)
    {
        $url = $this->client->getBaseUrl() . $resource;

        $method = strtolower($method);

        $this->response = $this->client->$method($url, [
            'body' => $this->query,
            'headers' => $this->headers,
        ]);

        // reset query params in case we make another request
        $this->query = array();
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

        $this->query[$parameter] = $value;
    }

    /**
     * @Then /^The response code should be (\d+)$/
     */
    public function theResponseCodeShouldBe($code)
    {
        assertEquals($code, $this->response->getStatusCode());
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
        if (null === $dog = $this->manager->getRepository('Fortune\Test\Entity\Dog')->findOneBy(array('id' => $id))) {
            $dog = new Fortune\Test\Entity\Dog;
            $dog->setName($dogExtra['name']);

            $this->manager->persist($dog);
            $this->manager->flush();
        }

        return $dog;
    }
}
