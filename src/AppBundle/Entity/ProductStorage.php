<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductStorage
 *
 * @ORM\Table(name="product_storage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductStorageRepository")
 */
class ProductStorage
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
     * @ORM\Column(name="lot", type="string", length=100, nullable=true)
     */
    private $lot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiryDate", type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer", nullable=true)
     */
    private $count;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="storage")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Booking", mappedBy="store")
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $booking;

    /**
     * @var
     * @ORM\Column(name="sup_date", type="datetime", nullable=true)
     */
    private $supDate;


    public function __toString()
    {
        return $this->id ? $this->product . ' Lot: ' . $this->lot . ', Exp: ' . $this->expiryDate->format('Y m d') . ', count ' . $this->count : 'New storage';
        // TODO: Implement __toString() method.
    }

    /**
     * set clone function configs
     */
    public function __clone() {

        $this->id = null;
        $this->lot = null;
        /*$this->count = 0;
        $this->expiryDate = null;
        $this->supDate = null;*/

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
     * Set lot
     *
     * @param string $lot
     *
     * @return ProductStorage
     */
    public function setLot($lot)
    {
        $this->lot = $lot;

        return $this;
    }

    /**
     * Get lot
     *
     * @return string
     */
    public function getLot()
    {
        return $this->lot;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return ProductStorage
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return ProductStorage
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
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return ProductStorage
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
     * @return mixed
     */
    public function getSupDate()
    {
        return $this->supDate;
    }

    /**
     * @param mixed $supDate
     */
    public function setSupDate($supDate)
    {
        $this->supDate = $supDate;
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
     * @return ProductStorage
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
