<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PreCipe
 *
 * @ORM\Table(name="msc_precipe")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\PreCipeRepository")
 */
class PreCipe
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
     * @var \Date
     *
     * @ORM\Column(name="data", type="date")
     */
    private $data;


    /**
     * @var string
     *
     * @ORM\Column(name="ufficiale_riunione", type="string", length=255)
     */
    private $ufficialeRiunione;


    /**
     * @var string
     *
     * @ORM\Column(name="public_reserved_status", type="string", length=255)
     */
    private $publicReservedStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="public_reserved_url", type="string", length=255)
     */
    private $publicReservedUrl;




    public function __construct() {
        $this->data = new \DateTime("0000-00-00");
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
     * Set data
     *
     * @param \DateTime $data
     *
     * @return PreCipe
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set ufficialeRiunione
     *
     * @param string $ufficialeRiunione
     *
     * @return PreCipe
     */
    public function setUfficialeRiunione($ufficialeRiunione)
    {
        $this->ufficialeRiunione = $ufficialeRiunione;

        return $this;
    }

    /**
     * Get ufficialeRiunione
     *
     * @return string
     */
    public function getUfficialeRiunione()
    {
        return $this->ufficialeRiunione;
    }

    /**
     * Set publicReservedStatus
     *
     * @param string $publicReservedStatus
     *
     * @return PreCipe
     */
    public function setPublicReservedStatus($publicReservedStatus)
    {
        $this->publicReservedStatus = $publicReservedStatus;

        return $this;
    }

    /**
     * Get publicReservedStatus
     *
     * @return string
     */
    public function getPublicReservedStatus()
    {
        return $this->publicReservedStatus;
    }

    /**
     * Set publicReservedUrl
     *
     * @param string $publicReservedUrl
     *
     * @return PreCipe
     */
    public function setPublicReservedUrl($publicReservedUrl)
    {
        $this->publicReservedUrl = $publicReservedUrl;

        return $this;
    }

    /**
     * Get publicReservedUrl
     *
     * @return string
     */
    public function getPublicReservedUrl()
    {
        return $this->publicReservedUrl;
    }
}
