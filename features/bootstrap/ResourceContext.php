<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;
use Fortune\Configuration\Configuration;
use Fortune\Test\Entity\Dog;
use Fortune\Test\Entity\Puppy;

class ResourceContext extends BaseContext
{
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
            $dog = new Dog;
            $dog->setName($data['name']);

            $this->getManager()->persist($dog);
            $this->getManager()->flush();
        }
    }

    /**
     * @Given /^The following puppies exist:$/
     */
    public function theFollowingPuppiesExist(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $puppy = new Puppy;
            $puppy->setName($data['name']);

            $this->getManager()->persist($puppy);
            $this->getManager()->flush();
        }
    }

    /**
     * @Given The puppy :puppyName belongs to dog :dogName
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
     * @Given The resource :resource requires :restriction
     * @Given The resource :resource requires :restriction :role 
     * @Given The resource :resource requires :restriction for access
     */
    public function theResourceRequiresAuthentication($resource, $restriction, $role = null)
    {
        $parsed = Yaml::parse(file_get_contents(__DIR__ . '/fixtures/config.yaml'));

        $parsed[$resource]['access_control'][$restriction] = $role ?: true;

        $dumper = new Dumper;

        file_put_contents(__DIR__ . '/fixtures/config.yaml', $dumper->dump($parsed, 3));
    }

    /**
     * @Given /^There is a config file containing the following:$/
     */
    public function thereIsAConfigFileContainingTheFollowing(PyStringNode $string)
    {
        file_put_contents(__DIR__ . '/fixtures/config.yaml', $string);
    }
}
