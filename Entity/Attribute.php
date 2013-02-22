<?php

namespace CCETC\DirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attribute
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Attribute
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
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Listing", mappedBy="attributes")
     */    
    private $listings;

    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Attribute
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add Listings
     *
     * @param \CCETC\DirectoryBundle\Entity\Listing $Listings
     * @return Attribute
     */
    public function addListing(\CCETC\DirectoryBundle\Entity\Listing $listings)
    {
        $this->listings[] = $listings;
    
        return $this;
    }

    /**
     * Remove Listings
     *
     * @param \CCETC\DirectoryBundle\Entity\Listing $Listings
     */
    public function removeListing(\CCETC\DirectoryBundle\Entity\Listing $listings)
    {
        $this->listings->removeElement($listings);
    }

    /**
     * Get Listings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListings()
    {
        return $this->listings;
    }
}