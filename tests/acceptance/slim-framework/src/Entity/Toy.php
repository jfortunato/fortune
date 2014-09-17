<?php

namespace Fortune\Test\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 * @Table(name="toys")
 */
class Toy
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $toy;

    /**
     * @ManyToOne(targetEntity="Puppy")
     * @JoinColumn(name="puppy_id", referencedColumnName="id")
     */
    private $puppy;

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
     * Gets the value of toy
     *
     * @return Toy
     */
    public function getToy()
    {
        return $this->toy;
    }

    /**
     * Sets the value of toy
     *
     * @param Toy $toy description
     *
     * @return Toy
     */
    public function setToy($toy)
    {
        $this->toy = $toy;
        return $this;
    }

    /**
     * Gets the value of puppy
     *
     * @return Puppy
     */
    public function getPuppy()
    {
        return $this->puppy;
    }

    /**
     * Sets the value of puppy
     *
     * @param Puppy $puppy description
     *
     * @return Toy
     */
    public function setPuppy(Puppy $puppy)
    {
        $this->puppy = $puppy;
        return $this;
    }
}
