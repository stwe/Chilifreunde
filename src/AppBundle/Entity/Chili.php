<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Chili
 *
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ChiliRepository")
 * @UniqueEntity(
 *     fields={"name", "public", "user"},
 *     errorPath="name",
 *     message="This name is already in use."
 * )
 *
 * @package AppBundle\Entity
 */
class Chili
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
     * @var string[]
     *
     * @ORM\Column(name="alternativeNames", type="simple_array", nullable=true)
     */
    private $alternativeNames;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Heat
     *
     * @ORM\ManyToOne(targetEntity="Heat")
     * @ORM\JoinColumn(name="heat_id", referencedColumnName="id")
     */
    private $heat;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=255, nullable=true)
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="growth", type="text", nullable=true)
     */
    private $growth;

    /**
     * @var Fruitcolor
     *
     * @ORM\ManyToOne(targetEntity="Fruitcolor")
     * @ORM\JoinColumn(name="fruitcolor_id", referencedColumnName="id")
     */
    private $fruitcolor;

    /**
     * @var Maturity
     *
     * @ORM\ManyToOne(targetEntity="Maturity")
     * @ORM\JoinColumn(name="maturity_id", referencedColumnName="id")
     */
    private $maturity;

    /**
     * @var Species
     *
     * @ORM\ManyToOne(targetEntity="Species", inversedBy="chilis")
     * @ORM\JoinColumn(name="species_id", referencedColumnName="id")
     */
    private $species;

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean", nullable=true)
     */
    private $public;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="chilis")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Plant", mappedBy="chili")
     */
    private $plants;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Media", mappedBy="chili", cascade={"persist"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ChiliUsage", inversedBy="chilis", cascade={"persist", "remove"})
     * @Assert\NotBlank()
     */
    private $usages;

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->plants = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->usages = new ArrayCollection();
        $this->public = true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Is the given User the owner of this Chili?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isOwner(User $user)
    {
        return $user === $this->getUser();
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
     * Set alternativeNames.
     *
     * @param array $alternativeNames
     *
     * @return $this
     */
    public function setAlternativeNames(array $alternativeNames = null)
    {
        $this->alternativeNames = $alternativeNames;

        return $this;
    }

    /**
     * Get alternativeNames.
     *
     * @return array 
     */
    public function getAlternativeNames()
    {
        return $this->alternativeNames;
    }

    /**
     * Set description.
     *
     * @param string $description
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
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set heat.
     *
     * @param Heat $heat
     *
     * @return $this
     */
    public function setHeat(Heat $heat)
    {
        $this->heat = $heat;

        return $this;
    }

    /**
     * Get heat.
     *
     * @return Heat
     */
    public function getHeat()
    {
        return $this->heat;
    }

    /**
     * Set origin.
     *
     * @param string $origin
     *
     * @return $this
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin.
     *
     * @return string 
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set growth.
     *
     * @param string $growth
     *
     * @return $this
     */
    public function setGrowth($growth)
    {
        $this->growth = $growth;

        return $this;
    }

    /**
     * Get growth.
     *
     * @return string
     */
    public function getGrowth()
    {
        return $this->growth;
    }

    /**
     * Set fruitcolor.
     *
     * @param Fruitcolor $fruitcolor
     *
     * @return $this
     */
    public function setFruitcolor(Fruitcolor $fruitcolor)
    {
        $this->fruitcolor = $fruitcolor;

        return $this;
    }

    /**
     * Get fruitcolor.
     *
     * @return Fruitcolor
     */
    public function getFruitcolor()
    {
        return $this->fruitcolor;
    }

    /**
     * Set maturity.
     *
     * @param Maturity $maturity
     *
     * @return $this
     */
    public function setMaturity(Maturity $maturity)
    {
        $this->maturity = $maturity;

        return $this;
    }

    /**
     * Set maturity.
     *
     * @return Maturity
     */
    public function getMaturity()
    {
        return $this->maturity;
    }

    /**
     * Set species.
     *
     * @param Species $species
     *
     * @return $this
     */
    public function setSpecies(Species $species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species.
     *
     * @return Species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set public.
     *
     * @param boolean $public
     *
     * @return $this
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Is public.
     *
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * Get public.
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add plant.
     *
     * @param Plant $plant
     *
     * @return $this
     */
    public function addPlant(Plant $plant)
    {
        if (!$this->plants->contains($plant)) {
            $this->plants->add($plant);
        }

        return $this;
    }

    /**
     * Remove plant.
     *
     * @param Plant $plant
     *
     * @return $this
     */
    public function removePlant(Plant $plant)
    {
        if ($this->plants->contains($plant)) {
            $this->plants->removeElement($plant);
        }

        return $this;
    }

    /**
     * Get plants.
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlants()
    {
        return $this->plants;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add image.
     *
     * @param Media $image
     *
     * @return $this
     */
    public function addImage(Media $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setChili($this);
        }

        return $this;
    }

    /**
     * Remove image.
     *
     * @param Media $image
     *
     * @return $this
     */
    public function removeImage(Media $image)
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }

    /**
     * Get all images.
     *
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add usage.
     *
     * @param ChiliUsage $usage
     *
     * @return $this
     */
    public function addUsage(ChiliUsage $usage)
    {
        if (!$this->usages->contains($usage)) {
            $this->usages->add($usage);
        }

        return $this;
    }

    /**
     * Remove usage.
     *
     * @param ChiliUsage $usage
     *
     * @return $this
     */
    public function removeUsage(ChiliUsage $usage)
    {
        if ($this->usages->contains($usage)) {
            $this->usages->removeElement($usage);
        }

        return $this;
    }

    /**
     * Get all usages.
     *
     * @return ArrayCollection
     */
    public function getUsages()
    {
        return $this->usages;
    }

    /**
     * Get seasons.
     *
     * @return array
     */
    public function getSeasons()
    {
        return array_map(
            function ($plant) {
                /** @var Plant $plant */
                return $plant->getSeason();
            },
            $this->plants->toArray()
        );
    }
}
