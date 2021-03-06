<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("position")
 */
class Menu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=50, unique=true)
     */
    private $slug;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", unique=true)
     *
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=60)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", length=128)
     */
    private $metaDescription;

    /**
     * @var boolean
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MenuItom", mappedBy="menu")
     */
    private $menuItoms;

    public function __toString()
    {
        return $this->id? $this->name : 'new Menu';
        // TODO: Implement __toString() method.
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Menu
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Menu
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Menu
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Menu
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Menu
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menuItoms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Menu
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Menu
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Menu
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add menuItom
     *
     * @param \AppBundle\Entity\MenuItom $menuItom
     *
     * @return Menu
     */
    public function addMenuItom(\AppBundle\Entity\MenuItom $menuItom)
    {
        $this->menuItoms[] = $menuItom;

        return $this;
    }

    /**
     * Remove menuItom
     *
     * @param \AppBundle\Entity\MenuItom $menuItom
     */
    public function removeMenuItom(\AppBundle\Entity\MenuItom $menuItom)
    {
        $this->menuItoms->removeElement($menuItom);
    }

    /**
     * Get menuItoms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenuItoms()
    {
        return $this->menuItoms;
    }
}
