<?php

namespace test\Fortune\Repository\fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use test\Fortune\Repository\fixtures\Dog;

class LoadDogData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dog = new Dog;
        $dog->setName('Fido');
        $manager->persist($dog);

        $dog = new Dog;
        $dog->setName('Daisy');
        $manager->persist($dog);

        $manager->flush();
    }
}
