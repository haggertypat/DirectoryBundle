<?php

namespace CCETC\DirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CCETC\DirectoryBundle\Entity\Page

 * @ORM\Table()
 * @ORM\Entity
 */
class Page
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $route
     *
     * @ORM\Column(name="route", type="string", length=255)
     */
    private $route;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     *
     * @ORM\Column(name="menuWeight", type="integer")
     */
    private $menuWeight = 0;

    /**
     * @var text $metaDescription
     *
     * @ORM\Column(name="metaDescription", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var text $heading
     *
     * @ORM\Column(name="heading", type="string", length=255, nullable=true)
     */
    private $heading;

    /**
     * @var text $metaTitle
     *
     * @ORM\Column(name="metaTitle", type="string", length=255, nullable=true)
     */
    private $metaTitle;

    /**
     * @var text $menuLabel
     *
     * @ORM\Column(name="menuLabel", type="string", length=255, nullable=true)
     */
    private $menuLabel;

    /**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;


    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        if($this->getRoute()) return $this->getRoute();
        else return "";
    }

    public function isChild()
    {
        return $this->getParent() && $this->getParent()->getId();
    }

    public function isParent()
    {
        return $this->getChildren() && count($this->getChildren()) > 0;
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
     * Set route
     *
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }
    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }

     /**
     * Set metaDescription
     *
     * @param text $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Get metaDescription
     *
     * @return text 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

     /**
     * Set menuLabel
     *
     * @param text $menuLabel
     */
    public function setMenuLabel($menuLabel)
    {
        $this->menuLabel = $menuLabel;
    }

    /**
     * Get menuLabel
     *
     * @return text 
     */
    public function getMenuLabel()
    {
        return $this->menuLabel;
    }

     /**
     * Set heading
     *
     * @param text $heading
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
    }

    /**
     * Get heading
     *
     * @return text 
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set metaTitle
     *
     * @param text $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * Get metaTitle
     *
     * @return text 
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

     /**
     * Set menuWeight
     *
     * @param text $menuWeight
     */
    public function setMenuWeight($menuWeight)
    {
        $this->menuWeight = $menuWeight;
    }

    /**
     * Get menuWeight
     *
     * @return text 
     */
    public function getMenuWeight()
    {
        return $this->menuWeight;
    }

     /**
     * Set parentPage
     *
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     */
    public function getParent()
    {
        return $this->parent;
    }

     /**
     * Add children
     *
     */
    public function addChild($child)
    {
        $this->children[] = $child;
    
        return $this;
    }

    /**
     * Remove children
     *
     */
    public function removeChild($child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
}