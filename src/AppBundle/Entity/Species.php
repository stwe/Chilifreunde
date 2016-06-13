<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Species
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("name")
 *
 * @package AppBundle\Entity
 */
class Species
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chili", mappedBy="species")
     */
    private $chilis;

    /**
     * Species constructor.
     */
    public function __construct()
    {
        $this->chilis = new ArrayCollection();
    }

    /**
     * @return string
     */
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
     * Set description.
     *
     * @param text $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
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
        $this->chilis[] = $chili;

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
        $this->chilis->removeElement($chili);

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
