<?php

namespace test\Fortune\Repository;

use test\Fortune\TestCase;
use Fortune\Repository\Driver\DoctrineResourceRepository;

class DoctrineResourceRepositoryTest extends TestCase
{
    public function setUp()
    {
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(__DIR__."/fixtures"), true);

        // database configuration parameters
        $conn = array(
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../db.sqlite',
        );

        // obtaining the entity manager
        $em = \Doctrine\ORM\EntityManager::create($conn, $config);

        // drop db then recreate
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->dropSchema($metadata);
        $tool->createSchema($metadata);

        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $loader->loadFromDirectory(__DIR__ . '/fixtures');
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());

        $this->repository = new DoctrineResourceRepository($em, 'test\Fortune\Repository\fixtures\Dog');
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
