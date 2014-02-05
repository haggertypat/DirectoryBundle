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

        if(!isset($this->route)) {
             //Lower case everything
            $route = strtolower($title);
            //Make alphanumeric (removes all other characters)
            $route = preg_replace("/[^a-z0-9_\s-]/", "", $route);
            //Clean up multiple dashes or whitespaces
            $route = preg_replace("/[\s-]+/", " ", $route);
            //Convert whitespaces and underscore to dash
            $route = preg_replace("/[\s_]/", "-", $route);
            $this->route = $route;
        }
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