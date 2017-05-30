<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cipe
 *
 * @ORM\Table(name="msc_cipe")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\CipeRepository")
 */
class Cipe
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
     * @ORM\Column(name="giorno", type="string", length=255)
     */
    private $giorno;

    /**
     * @var string
     *
     * @ORM\Column(name="ora", type="string", length=255)
     */
    private $ora;


    /**
     * @var string
     *
     * @ORM\Column(name="sede", type="string", length=255)
     */
    private $sede;


    /**
     * @var \int
     *
     * @ORM\Column(name="id_presidente", type="integer")
     */
    private $idPresidente;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_segretario", type="integer")
     */
    private $idSegretario;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_direttore", type="integer")
     */
    private $idDirettore;


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
        $this->publicReservedStatus = "";
        $this->publicReservedUrl = "";
        $this->ufficialeRiunione = "";
        $this->giorno = "";
        $this->ora = "";
        $this->sede = "";
        $this->idPresidente = "";
        $this->idDirettore = "";
        $this->idSegretario = "";
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
     * @return Cipe
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
     * @return Cipe
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
     * Set giorno
     *
     * @param string $giorno
     *
     * @return Cipe
     */
    public function setGiorno($giorno)
    {
        $this->giorno = $giorno;

        return $this;
    }

    /**
     * Get giorno
     *
     * @return string
     */
    public function getGiorno()
    {
        return $this->giorno;
    }

    /**
     * Set ora
     *
     * @param string $ora
     *
     * @return Cipe
     */
    public function setOra($ora)
    {
        $this->ora = $ora;

        return $this;
    }

    /**
     * Get ora
     *
     * @return string
     */
    public function getOra()
    {
        return $this->ora;
    }

    /**
     * Set sede
     *
     * @param string $sede
     *
     * @return Cipe
     */
    public function setSede($sede)
    {
        $this->sede = $sede;

        return $this;
    }

    /**
     * Get sede
     *
     * @return string
     */
    public function getSede()
    {
        return $this->sede;
    }

    /**
     * Set idPresidente
     *
     * @param integer $idPresidente
     *
     * @return Cipe
     */
    public function setIdPresidente($idPresidente)
    {
        $this->idPresidente = $idPresidente;

        return $this;
    }

    /**
     * Get idPresidente
     *
     * @return integer
     */
    public function getIdPresidente()
    {
        return $this->idPresidente;
    }

    /**
     * Set idSegretario
     *
     * @param integer $idSegretario
     *
     * @return Cipe
     */
    public function setIdSegretario($idSegretario)
    {
        $this->idSegretario = $idSegretario;

        return $this;
    }

    /**
     * Get idSegretario
     *
     * @return integer
     */
    public function getIdSegretario()
    {
        return $this->idSegretario;
    }

    /**
     * Set idDirettore
     *
     * @param integer $idDirettore
     *
     * @return Cipe
     */
    public function setIdDirettore($idDirettore)
    {
        $this->idDirettore = $idDirettore;

        return $this;
    }

    /**
     * Get idDirettore
     *
     * @return integer
     */
    public function getIdDirettore()
    {
        return $this->idDirettore;
    }

    /**
     * Set publicReservedStatus
     *
     * @param string $publicReservedStatus
     *
     * @return Cipe
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
     * @return Cipe
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
