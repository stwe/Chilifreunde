<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Plant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PlantRepository")
 *
 * @package AppBundle\Entity
 */
class Plant
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
     * @var Chili
     *
     * @ORM\ManyToOne(targetEntity="Chili", inversedBy="plants")
     * @ORM\JoinColumn(name="chili_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $chili;

    /**
     * @var Season
     *
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="plants")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id", nullable=false)
     */
    private $season;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min="0",
     *     max="1000"
     * )
     */
    private $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="sowing", type="integer", nullable=false)
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\NotBlank()
     * @Assert\Range(
     *     min="0",
     *     max="1000"
     * )
     */
    private $sowing;

    /**
     * @var Source
     *
     * @ORM\ManyToOne(targetEntity="Source")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=true)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->quantity . ' x ' . $this->chili->getName();
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
     * Set quantity.
     *
     * @param integer $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set sowing.
     *
     * @param integer $sowing
     *
     * @return $this
     */
    public function setSowing($sowing)
    {
        $this->sowing = $sowing;

        return $this;
    }

    /**
     * Get sowing.
     *
     * @return integer
     */
    public function getSowing()
    {
        return $this->sowing;
    }

    /**
     * Set chili.
     *
     * @param Chili|null $chili
     *
     * @return $this
     */
    public function setChili(Chili $chili = null)
    {
        $this->chili = $chili;

        return $this;
    }

    /**
     * Get chili.
     *
     * @return $this
     */
    public function getChili()
    {
        return $this->chili;
    }

    /**
     * Set season.
     *
     * @param Season|null $season
     *
     * @return $this
     */
    public function setSeason(Season $season = null)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season.
     *
     * @return \AppBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set source.
     *
     * @param Source $source
     *
     * @return $this
     */
    public function setSource(Source $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
