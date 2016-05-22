<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class ChiliUsage
 *
 * @ORM\Table
 * @ORM\Entity
 * @UniqueEntity("name")
 *
 * @package AppBundle\Entity
 */
class ChiliUsage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Chili", mappedBy="usages")
     */
    private $chilis;

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->chilis = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id.
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add chili.
     *
     * @param Chili $chili
     *
     * @return $this
     */
    public function addChili(Chili $chili)
    {
        if (!$this->chilis->contains($chili)) {
            $this->chilis->add($chili);
        }

        return $this;
    }

    /**
     * Remove chili.
     *
     * @param Chili $chili
     *
     * @return $this
     */
    public function removeChili(Chili $chili)
    {
        if ($this->chilis->contains($chili)) {
            $this->chilis->removeElement($chili);
        }

        return $this;
    }

    /**
     * Get chilis.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChilis()
    {
        return $this->chilis;
    }
}
