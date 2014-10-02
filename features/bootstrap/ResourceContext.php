<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;
use Fortune\Configuration\Configuration;
use Fortune\Test\Entity\Dog;
use Fortune\Test\Entity\Puppy;
use Fortune\Test\Entity\Toy;
use Behat\Behat\Context\SnippetAcceptingContext;

class ResourceContext extends BaseContext implements SnippetAcceptingContext
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
     * @Given The following toys exist:
     */
    public function theFollowingToysExist(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $toy = new Toy;
            $toy->setToy($data['toy']);

            $this->getManager()->persist($toy);
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
     * @Given The toy :toy belongs to puppy :puppy
     */
    public function theToyBelongsToPuppy($toy, $puppyName)
    {
        $toy = $this->getManager()->getRepository('Fortune\Test\Entity\Toy')->findOneBy(array('toy' => $toy));
        $puppy = $this->getManager()->getRepository('Fortune\Test\Entity\Puppy')->findOneBy(array('name' => $puppyName));

        $toy->setPuppy($puppy);

        $this->getManager()->persist($toy);
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

    /**
     * @Given The class file :file contains:
     */
    public function theClassFileContains($file, PyStringNode $string)
    {
        $dirname = dirname($file);
        if (!file_exists($dirname)) {
            mkdir($dirname, 0777, true);
        }

        file_put_contents($file, $string->getRaw());

        require_once $file;
    }
}
