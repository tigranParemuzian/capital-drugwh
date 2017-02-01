<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * InvoiceSettings
 *
 * @ORM\Table(name="invoice_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceSettingsRepository")
 */
class InvoiceSettings
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
     * @var int
     *
     * @ORM\Column(name="terms", type="integer")
     */
    private $terms;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_via", type="string", length=50)
     */
    private $shipVia;

    /**
     * @var int
     *
     * @ORM\Column(name="ship_date", type="integer")
     */
    private $shipDate;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set terms
     *
     * @param integer $terms
     *
     * @return InvoiceSettings
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * Get terms
     *
     * @return int
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Set shipVia
     *
     * @param string $shipVia
     *
     * @return InvoiceSettings
     */
    public function setShipVia($shipVia)
    {
        $this->shipVia = $shipVia;

        return $this;
    }

    /**
     * Get shipVia
     *
     * @return string
     */
    public function getShipVia()
    {
        return $this->shipVia;
    }

    /**
     * Set shipDate
     *
     * @param integer $shipDate
     *
     * @return InvoiceSettings
     */
    public function setShipDate($shipDate)
    {
        $this->shipDate = $shipDate;

        return $this;
    }

    /**
     * Get shipDate
     *
     * @return int
     */
    public function getShipDate()
    {
        return $this->shipDate;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return InvoiceSettings
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
     * @return InvoiceSettings
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
}
