<?php

namespace test\Fortune\Repository\fixtures;

/**
 * @Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 * @Table(name="dogs")
 */
class Dog
{
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
     * @return Dog
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
