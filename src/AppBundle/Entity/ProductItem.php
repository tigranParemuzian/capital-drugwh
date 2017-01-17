<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProductIngridient
 *
 * @ORM\Table(name="product_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductItemRepository")
 */
class ProductItem
{
    const CT = 0;
    const ML = 1;


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
     * @ORM\Column(name="manufacturer", type="string", length=255)
     */
    private $manufacturer;

    /**
     * @var string
     *
     * @ORM\Column(name="nds", type="string", length=255, unique=true)
     */
    private $nds;

    /**
     * @var float
     *
     * @ORM\Column(name="size", type="float")
     */
    private $size;

    /**
     * @var int
     *
     * @ORM\Column(name="unit", type="smallint")
     */
    private $unit;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Product", mappedBy="productItem")
     */
    private $product;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="alternative")
     */
    private $product_alternative;

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
        return $this->id ? $this->manufacturer . ' ' . $this->size . ' ' . $this->unit : 'new Product item';
        // TODO: Implement __toString() method.
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_alternative = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set manufacturer
     *
     * @param string $manufacturer
     *
     * @return ProductItem
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Set nds
     *
     * @param string $nds
     *
     * @return ProductItem
     */
    public function setNds($nds)
    {
        $this->nds = $nds;

        return $this;
    }

    /**
     * Get nds
     *
     * @return string
     */
    public function getNds()
    {
        return $this->nds;
    }

    /**
     * Set size
     *
     * @param float $size
     *
     * @return ProductItem
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set unit
     *
     * @param integer $unit
     *
     * @return ProductItem
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return integer
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return ProductItem
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
     * @return ProductItem
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
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return ProductItem
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add productAlternative
     *
     * @param \AppBundle\Entity\Product $productAlternative
     *
     * @return ProductItem
     */
    public function addProductAlternative(\AppBundle\Entity\Product $productAlternative)
    {
        $this->product_alternative[] = $productAlternative;

        return $this;
    }

    /**
     * Remove productAlternative
     *
     * @param \AppBundle\Entity\Product $productAlternative
     */
    public function removeProductAlternative(\AppBundle\Entity\Product $productAlternative)
    {
        $this->product_alternative->removeElement($productAlternative);
    }

    /**
     * Get productAlternative
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductAlternative()
    {
        return $this->product_alternative;
    }
}
