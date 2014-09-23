<?php

namespace test\Fortune\Repository;

use test\Fortune\TestCase;
use Fortune\Repository\DoctrineResourceRepository;

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

    public function testFindAllReturnsAllAsArray()
    {
        $resources = $this->repository->findAll();

        $this->assertTrue(is_array($resources[0]));
        $this->assertTrue(is_array($resources[1]));
        $this->assertSame(1, $resources[0]['id']);
        $this->assertSame(2, $resources[1]['id']);
    }

    public function testFindReturnsASingleResourceAsArray()
    {
        $resource = $this->repository->find(1);

        $this->assertTrue(is_array($resource));
        $this->assertSame(1, $resource['id']);
    }

    public function testCanCreateResourceWithValidInput()
    {
        $resource = $this->repository->create(['name' => 'Foo']);

        $this->assertTrue(is_array($resource));
        $this->assertSame(3, $resource['id']);
        $this->assertSame('Foo', $resource['name']);
    }
}
