<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
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
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="manufacturer", type="string", length=255)
     */
    private $manufacturer;

    /**
     * @var int
     *
     * @ORM\Column(name="ndc", type="integer", unique=true)
     */
    private $ndc;

    /**
     * @var string
     *
     * @ORM\Column(name="alternative", type="string", length=255)
     */
    private $alternative;

    /**
     * @var string
     *
     * @ORM\Column(name="gpi", type="string", length=255)
     */
    private $gpi;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=50)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=10, unique=true)
     */
    private $unit;

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
     * Set manufacturer
     *
     * @param string $manufacturer
     *
     * @return Product
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
     * Set ndc
     *
     * @param integer $ndc
     *
     * @return Product
     */
    public function setNdc($ndc)
    {
        $this->ndc = $ndc;

        return $this;
    }

    /**
     * Get ndc
     *
     * @return int
     */
    public function getNdc()
    {
        return $this->ndc;
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
     * Set gpi
     *
     * @param string $gpi
     *
     * @return Product
     */
    public function setGpi($gpi)
    {
        $this->gpi = $gpi;

        return $this;
    }

    /**
     * Get gpi
     *
     * @return string
     */
    public function getGpi()
    {
        return $this->gpi;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Product
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Product
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
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
     * @return int
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
     * @return int
     */
    public function getAvalible()
    {
        return $this->avalible;
    }
}

