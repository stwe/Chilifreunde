<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Heat
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("heat")
 *
 * @package AppBundle\Entity
 */
class Heat
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
     * @var integer
     *
     * @ORM\Column(name="value", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min="0",
     *     max="11"
     * )
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="heat", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $heat;

    /**
     * @var integer
     *
     * @ORM\Column(name="scovilleMin", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min="0",
     *     max="16000000"
     * )
     */
    private $scovilleMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="scovilleMax", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min="0",
     *     max="16000000"
     * )
     */
    private $scovilleMax;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->heat . ' (' . $this->scovilleMin . ' - ' . $this->scovilleMax . ' Scoville)';
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
     * Set value.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set heat.
     *
     * @param string $heat
     *
     * @return $this
     */
    public function setHeat($heat)
    {
        $this->heat = $heat;

        return $this;
    }

    /**
     * Get heat.
     *
     * @return string
     */
    public function getHeat()
    {
        return $this->heat;
    }

    /**
     * Set scovilleMin.
     *
     * @param integer $scovilleMin
     *
     * @return $this
     */
    public function setScovilleMin($scovilleMin)
    {
        $this->scovilleMin = $scovilleMin;

        return $this;
    }

    /**
     * Get scovilleMin.
     *
     * @return integer 
     */
    public function getScovilleMin()
    {
        return $this->scovilleMin;
    }

    /**
     * Set scovilleMax.
     *
     * @param integer $scovilleMax
     *
     * @return $this
     */
    public function setScovilleMax($scovilleMax)
    {
        $this->scovilleMax = $scovilleMax;

        return $this;
    }

    /**
     * Get scovilleMax.
     *
     * @return integer 
     */
    public function getScovilleMax()
    {
        return $this->scovilleMax;
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
}
