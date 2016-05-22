<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PostRepository")
 *
 * @package AppBundle\Entity
 */
class Post
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var Season
     *
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="posts")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id", nullable=false)
     */
    private $season;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_start", type="datetime", nullable=true)
     */
    private $eventStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_end", type="datetime", nullable=true)
     */
    private $eventEnd;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="published_at", type="datetime")
     */
    private $publishedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Media", mappedBy="post", cascade={"persist"})
     */
    private $images;

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Is the given User the owner of this Post?
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
     * Is date valid.
     *
     * @Assert\isTrue(message = "The start date must be before the end date")
     *
     * @return bool
     */
    public function isDatesValid()
    {
        if ($this->eventStart && $this->eventEnd) {
            return ($this->eventStart < $this->eventEnd);
        } else {
            return true;
        }
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
     * Set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set season.
     *
     * @param Season $season
     *
     * @return $this
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season.
     *
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set eventStart.
     *
     * @param \DateTime $eventStart
     *
     * @return $this
     */
    public function setEventStart(\DateTime $eventStart)
    {
        $this->eventStart = $eventStart;

        return $this;
    }

    /**
     * Get eventStart.
     *
     * @return \DateTime
     */
    public function getEventStart()
    {
        return $this->eventStart;
    }

    /**
     * Set eventEnd.
     *
     * @param \DateTime $eventEnd
     *
     * @return $this
     */
    public function setEventEnd(\DateTime $eventEnd)
    {
        $this->eventEnd = $eventEnd;

        return $this;
    }

    /**
     * Get eventEnd.
     *
     * @return \DateTime
     */
    public function getEventEnd()
    {
        return $this->eventEnd;
    }

    /**
     * Set publishedAt.
     *
     * @param \DateTime $publishedAt
     *
     * @return $this
     */
    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt.
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
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
            $image->setPost($this);
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
}
