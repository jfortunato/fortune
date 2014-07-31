<?php

use Behat\Behat\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Behat\Behat\Context\Initializer\InitializerInterface;
use Behat\Behat\Context\ContextInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


class DoctrineAwareExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('services.yml');
    }
}

class DoctrineAwareInitializer implements InitializerInterface
{
    protected $manager;

    public function __construct()
    {
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../../bootstrap/src/Entity"), true);

        // database configuration parameters
        $conn = array(
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../../bootstrap/db.sqlite',
        );

        // obtaining the entity manager
        $this->manager = Doctrine\ORM\EntityManager::create($conn, $config);
    }

    public function supports(ContextInterface $context)
    {
        return $context instanceof DoctrineAwareInterface;
    }

    public function initialize(ContextInterface $context)
    {
        $context->setManager($this->manager);
    }
}

interface DoctrineAwareInterface {
    public function setManager(EntityManager $manager);
}

return new DoctrineAwareExtension;
