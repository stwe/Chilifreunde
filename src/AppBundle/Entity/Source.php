<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Source
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SourceRepository")
 * @UniqueEntity(
 *     fields={"name", "public", "user"},
 *     errorPath="name",
 *     message="This name is already in use."
 * )
 *
 * @package AppBundle\Entity
 */
class Source
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
     * @ORM\OneToMany(targetEntity="Address", mappedBy="source", cascade={"persist"})
     */
    private $addresses;

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
     * Source constructor.
     */
    public function __construct()
    {
        $this->public = true;
        $this->addresses = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Is the given User the owner of this Source?
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
     * Add address.
     *
     * @param Address $address
     *
     * @return $this
     */
    public function addAddress(Address $address)
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setSource($this);
        }

        return $this;
    }

    /**
     * Remove address.
     *
     * @param Address $address
     *
     * @return $this
     */
    public function removeAddress(Address $address)
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }

        return $this;
    }

    /**
     * Get addresses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
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
}
