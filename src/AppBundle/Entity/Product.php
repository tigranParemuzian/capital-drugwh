<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ProductItem", inversedBy="product")
     * @ORM\JoinColumn(name="product_item", referencedColumnName="id")
     */
    private $productItem;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductItem", inversedBy="product_alternative")
     * @ORM\JoinColumn(name="alternative", referencedColumnName="id")
     *
     */
    private $alternative;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="pricingCode", type="string", length=255)
     */
    private $pricingCode;

    /**
     * @var int
     *
     * @ORM\Column(name="avalible", type="integer")
     */
    private $avalible;

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

    public function __toString()
    {
        return $this->id ? $this->productItem->getManufacturer() . ' ' . $this->productItem->getSize() . ' ' . $this->productItem->getUnit() : 'new Product item';
        // TODO: Implement __toString() method.
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
     *
     * @return Product
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
     * @return Product
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
     * Set count
     *
     * @param integer $count
     *
     * @return Product
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set pricingCode
     *
     * @param string $pricingCode
     *
     * @return Product
     */
    public function setPricingCode($pricingCode)
    {
        $this->pricingCode = $pricingCode;

        return $this;
    }

    /**
     * Get pricingCode
     *
     * @return string
     */
    public function getPricingCode()
    {
        return $this->pricingCode;
    }

    /**
     * Set avalible
     *
     * @param integer $avalible
     *
     * @return Product
     */
    public function setAvalible($avalible)
    {
        $this->avalible = $avalible;

        return $this;
    }

    /**
     * Get avalible
     *
     * @return integer
     */
    public function getAvalible()
    {
        return $this->avalible;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Product
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
     * @return Product
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
     * Set productItem
     *
     * @param \AppBundle\Entity\ProductItem $productItem
     *
     * @return Product
     */
    public function setProductItem(\AppBundle\Entity\ProductItem $productItem = null)
    {
        $this->productItem = $productItem;

        return $this;
    }

    /**
     * Get productItem
     *
     * @return \AppBundle\Entity\ProductItem
     */
    public function getProductItem()
    {
        return $this->productItem;
    }

    /**
     * Set alternative
     *
     * @param \AppBundle\Entity\ProductItem $alternative
     *
     * @return Product
     */
    public function setAlternative(\AppBundle\Entity\ProductItem $alternative = null)
    {
        $this->alternative = $alternative;

        return $this;
    }

    /**
     * Get alternative
     *
     * @return \AppBundle\Entity\ProductItem
     */
    public function getAlternative()
    {
        return $this->alternative;
    }
}
