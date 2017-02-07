<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation as Serializer;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 * @GRID\Column(id="name", type="text")
 * @GRID\Source(columns="id, name, productItem.strength, productItem.manufacturer, productItem.nds, slug, count, price, unit_size, productItem.size, productItem.unit")
 * @GRID\Column(id="unit_size", type="join", title="Size", columns={"productItem.size", "productItem.unit"}, size=30)
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @var
     * @GRID\Column(title="show count")
     */
    private $showcount;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @GRID\Column(title="Name")
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @GRID\Column(visible=false)
     */
    private $slug;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ProductItem", inversedBy="product")
     * @ORM\JoinColumn(name="product_item", referencedColumnName="id")
     * @GRID\Column(field="productItem.nds", title="NDS", first="")
     * @GRID\Column(field="productItem.manufacturer", title="Manufacturer")
     * @GRID\Column(field="productItem.size", title="Size", filter=false, visible=false)
     * @GRID\Column(field="productItem.unit", title="Unit", filterable=false, visible=false)
     * @GRID\Column(field="productItem.strength", title="Strength", filterable=true)
     */
    private $productItem;

    /**
     * @ORM\Column(name="alternative", type="string", length=100, nullable=true)
     */
    private $alternative;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     * @GRID\Column(align="center", title="Count", size=30, type="number", filter="input")
     */
    private $count;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     * @GRID\Column(align="center", title="Price", size=30)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="pricingCode", type="string", length=255, nullable=true)
     */
    private $pricingCode;

    /**
     * @var int
     *
     * @ORM\Column(name="avalible", type="integer", nullable=true)
     * @GRID\Column(title="Is avalible", type="boolean")
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

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Booking", mappedBy="product")
     */
    private $booking;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="product")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     * @GRID\Column(field="category.name", title="Category Name", filter="select")
     */
    private $category;

    public function __toString()
    {
        return $this->id ? $this->name . ' ' . $this->productItem->getNds() : 'new Product item';
        // TODO: Implement __toString() method.
    }


    public function getShowCount(){
        return $this->showcount . 'ddd';
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
        $this->price = round($price,2);

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return round($this->price, 2);
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
     * @param string $alternative
     *
     * @return Product
     */
    public function setAlternative($alternative)
    {
        $this->alternative = $alternative;

        return $this;
    }

    /**
     * Get alternative
     *
     * @return string
     */
    public function getAlternative()
    {
        return $this->alternative;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->booking = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add booking
     *
     * @param \AppBundle\Entity\Booking $booking
     *
     * @return Product
     */
    public function addBooking(\AppBundle\Entity\Booking $booking)
    {
        $this->booking[] = $booking;

        return $this;
    }

    /**
     * Remove booking
     *
     * @param \AppBundle\Entity\Booking $booking
     */
    public function removeBooking(\AppBundle\Entity\Booking $booking)
    {
        $this->booking->removeElement($booking);
    }

    /**
     * Get booking
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooking()
    {
        return $this->booking;
    }
}
