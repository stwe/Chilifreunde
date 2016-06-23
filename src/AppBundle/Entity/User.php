<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 *
 * @package AppBundle\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chili", mappedBy="user")
     */
    private $chilis;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Location", mappedBy="user")
     */
    private $locations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Season", mappedBy="user")
     */
    private $seasons;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     */
    private $posts;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Address", mappedBy="user", cascade={"persist"})
     */
    private $addresses;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity_at", type="datetime", nullable=true)
     */
    private $lastActivityAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="approved", type="boolean")
     */
    private $approved;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->chilis = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->approved = false;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

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

    /**
     * Add location.
     *
     * @param Location $location
     *
     * @return $this
     */
    public function addLocation(Location $location)
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * Remove location.
     *
     * @param Location $location
     *
     * @return $this
     */
    public function removeLocation(Location $location)
    {
        $this->locations->removeElement($location);

        return $this;
    }

    /**
     * Get locations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Add season.
     *
     * @param Season $season
     *
     * @return $this
     */
    public function addSeason(Season $season)
    {
        $this->seasons[] = $season;

        return $this;
    }

    /**
     * Remove season.
     *
     * @param Season $season
     *
     * @return $this
     */
    public function removeSeason(Season $season)
    {
        $this->seasons->removeElement($season);

        return $this;
    }

    /**
     * Get seasons.
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * Add post.
     *
     * @param Post $post
     *
     * @return $this
     */
    public function addPost(Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post.
     *
     * @param Post $post
     *
     * @return $this
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     * Get posts.
     *
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
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
            $address->setUser($this);
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
     * Set last activity.
     *
     * @param \DateTime $lastActivityAt
     *
     * @return $this
     */
    public function setLastActivityAt($lastActivityAt)
    {
        $this->lastActivityAt = $lastActivityAt;

        return $this;
    }

    /**
     * Get last activity.
     *
     * @return \DateTime
     */
    public function getLastActivityAt()
    {
        return $this->lastActivityAt;
    }

    /**
     * Whether the user is active or not.
     *
     * @return boolean
     */
    public function isActiveNow()
    {
        // Delay during wich the user will be considered as still active
        $delay = new \DateTime('2 minutes ago');

        return ($this->getLastActivityAt() > $delay);
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * Set approved.
     *
     * @param boolean $approved
     *
     * @return $this
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Is approved.
     *
     * @return boolean
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * Get approved.
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Get expires at.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Get credentials expires at.
     *
     * @return \DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }
}
