<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tags
 *
 * @ORM\Table(name="msc_uffici")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UfficiRepository")
 */
class Uffici
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
     * @ORM\Column(name="codice", type="integer")
     */
    private $codice;

    /**
     * @var string
     *
     * @ORM\Column(name="codice_direzione", type="string", length=255)
     */
    private $codiceDirezione;

    /**
     * @var string
     *
     * @ORM\Column(name="denominazione", type="string", length=255)
     */
    private $denominazione;

    /**
     * @var string
     *
     * @ORM\Column(name="ordine_ufficio", type="string", length=255)
     */
    private $ordineUfficio;

    /**
     * @var string
     *
     * @ORM\Column(name="disattivo_ufficio", type="string", length=255)
     */
    private $disattivo_ufficio;


    /**
     * @var string
     *
     * @ORM\Column(name="solo_delibere", type="string", length=255)
     */
    private $soloDelibere;




    public function __construct() {
        $this->disattivo_ufficio = 0;
        $this->soloDelibere = 0;
        $this->ordineUfficio = 0;
        $this->codiceDirezione = 0;
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
     * Set codice
     *
     * @param integer $codice
     *
     * @return Uffici
     */
    public function setCodice($codice)
    {
        $this->codice = $codice;

        return $this;
    }

    /**
     * Get codice
     *
     * @return integer
     */
    public function getCodice()
    {
        return $this->codice;
    }

    /**
     * Set codiceDirezione
     *
     * @param string $codiceDirezione
     *
     * @return Uffici
     */
    public function setCodiceDirezione($codiceDirezione)
    {
        $this->codiceDirezione = $codiceDirezione;

        return $this;
    }

    /**
     * Get codiceDirezione
     *
     * @return string
     */
    public function getCodiceDirezione()
    {
        return $this->codiceDirezione;
    }

    /**
     * Set denominazione
     *
     * @param string $denominazione
     *
     * @return Uffici
     */
    public function setDenominazione($denominazione)
    {
        $this->denominazione = $denominazione;

        return $this;
    }

    /**
     * Get denominazione
     *
     * @return string
     */
    public function getDenominazione()
    {
        return $this->denominazione;
    }

    /**
     * Set ordineUfficio
     *
     * @param string $ordineUfficio
     *
     * @return Uffici
     */
    public function setOrdineUfficio($ordineUfficio)
    {
        $this->ordineUfficio = $ordineUfficio;

        return $this;
    }

    /**
     * Get ordineUfficio
     *
     * @return string
     */
    public function getOrdineUfficio()
    {
        return $this->ordineUfficio;
    }

    /**
     * Set disattivoUfficio
     *
     * @param string $disattivoUfficio
     *
     * @return Uffici
     */
    public function setDisattivoUfficio($disattivoUfficio)
    {
        $this->disattivo_ufficio = $disattivoUfficio;

        return $this;
    }

    /**
     * Get disattivoUfficio
     *
     * @return string
     */
    public function getDisattivoUfficio()
    {
        return $this->disattivo_ufficio;
    }

    /**
     * Set soloDelibere
     *
     * @param string $soloDelibere
     *
     * @return Uffici
     */
    public function setSoloDelibere($soloDelibere)
    {
        $this->soloDelibere = $soloDelibere;

        return $this;
    }

    /**
     * Get soloDelibere
     *
     * @return string
     */
    public function getSoloDelibere()
    {
        return $this->soloDelibere;
    }
}
