<?php

namespace Fortune\Test\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity(repositoryClass="Fortune\Test\Entity\PuppyRepository")
 * @Table(name="puppies")
 */
class Puppy
{
    public static $requiresAuthentication = false;
    public static $requiresRole = null;
    public static $requiresOwner = false;

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="Dog")
     * @JoinColumn(name="dog_id", referencedColumnName="id")
     */
    private $dog;

    /**
     * Gets the value of id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name
     *
     * @param string $name description
     *
     * @return Puppy
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the value of dog
     *
     * @return Dog
     */
    public function getDog()
    {
        return $this->dog;
    }

    /**
     * Sets the value of dog
     *
     * @param Dog $dog description
     *
     * @return Puppy
     */
    public function setDog(Dog $dog)
    {
        $this->dog = $dog;
        return $this;
    }
}
