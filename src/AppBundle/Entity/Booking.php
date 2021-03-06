<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    const IS_NEW = 0;
    const IS_ORDERED = 1;
    const IS_CHANGED = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"booking_list"})
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     * @Groups({"booking_list"})
     */
    private $count;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="float")
     * @Groups({"booking_list"})
     */
    private $cost;

    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float")
     * @Groups({"booking_list"})
     */
    private $subTotal;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="booking")
     * @ORM\JoinColumn(name="products_is", referencedColumnName="id")
     * @Groups({"booking_list"})
     */
    private $product;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="booking")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $invoice;

    /**
     * @var
     * @ORM\Column(name="status", type="smallint")
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
     * @ORM\Column(name="lot", type="string", length=50, nullable=true)
     */
    private $lot;

    /**
     * @var
     * @ORM\Column(name="expiry_date", type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @var
     * @ORM\Column(name="ship_date", type="datetime", nullable=true)
     */
    private $shipDate;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductStorage", inversedBy="booking")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="id")
     */
    private $store;

    public function __toString()
    {
        return $this->id ? $this->product . ', count: ' . $this->count  .
            ', Lot:' . $this->lot :"new order";
        // TODO: Implement __toString() method.
    }

    /**
     * set clone function configs
     */
    public function __clone() {

        $this->id = null;
        $this->count = 0;
        $this->lot = null;
        $this->expiryDate = null;
        $this->shipDate = null;

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
     * Set count
     *
     * @param integer $count
     *
     * @return Booking
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
     * Set cost
     *
     * @param integer $cost
     *
     * @return Booking
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set subTotal
     *
     * @param float $subTotal
     *
     * @return Booking
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    /**
     * Get subTotal
     *
     * @return float
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\User $client
     *
     * @return Booking
     */
    public function setClient(\AppBundle\Entity\User $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\User
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Booking
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
     * Set status
     *
     * @param integer $status
     *
     * @return Booking
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
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
     * @return Booking
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
     * @return Booking
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
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     *
     * @return Booking
     */
    public function setInvoice(\AppBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \AppBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set lot
     *
     * @param string $lot
     *
     * @return Booking
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
     * @return Booking
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
     * Set shipDate
     *
     * @param \DateTime $shipDate
     *
     * @return Booking
     */
    public function setShipDate($shipDate)
    {
        $this->shipDate = $shipDate;

        return $this;
    }

    /**
     * Get shipDate
     *
     * @return \DateTime
     */
    public function getShipDate()
    {
        return $this->shipDate;
    }

    /**
     * Set store
     *
     * @param \AppBundle\Entity\ProductStorage $store
     *
     * @return Booking
     */
    public function setStore(\AppBundle\Entity\ProductStorage $store = null)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return \AppBundle\Entity\ProductStorage
     */
    public function getStore()
    {
        return $this->store;
    }
}
