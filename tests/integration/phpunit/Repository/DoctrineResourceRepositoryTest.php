<?php

namespace test\Fortune\Repository;

use test\Fortune\TestCase;
use Fortune\Repository\Driver\DoctrineResourceRepository;

class DoctrineResourceRepositoryTest extends TestCase
{
    public function setUp()
    {
        $container = new \Fortune\Test\Container;

        // drop db then recreate
        $container->dbRecreator->recreateDatabase();

        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $loader->loadFromDirectory(__DIR__ . '/fixtures');
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($container->doctrine);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($container->doctrine, $purger);
        $executor->execute($loader->getFixtures());

        $this->repository = new DoctrineResourceRepository($container->doctrine, 'test\Fortune\Repository\fixtures\Dog');
    }

    public function testFindAllReturnsAnArray()
    {
        $resources = $this->repository->findAll();

        $this->assertTrue(is_array($resources));
    }

    public function testFindAllReturnsAllResourceObjects()
    {
        $resources = $this->repository->findAll();

        $this->assertInstanceOf('test\Fortune\Repository\fixtures\Dog', $resources[0]);
        $this->assertInstanceOf('test\Fortune\Repository\fixtures\Dog', $resources[1]);
        $this->assertSame(1, $resources[0]->getId());
        $this->assertSame(2, $resources[1]->getId());
    }

    public function testFindReturnsASingleResourceObject()
    {
        $resource = $this->repository->find(1);

        $this->assertInstanceOf('test\Fortune\Repository\fixtures\Dog', $resource);
        $this->assertSame(1, $resource->getId());
    }

    public function testCanCreateResourceWithValidInput()
    {
        $resource = $this->repository->create(['name' => 'Foo']);

        $this->assertInstanceOf('test\Fortune\Repository\fixtures\Dog', $resource);
        $this->assertSame(3, $resource->getId());
        $this->assertSame('Foo', $resource->getName());
    }
}
