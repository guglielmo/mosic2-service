<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Titolari
 *
 * @ORM\Table(name="msc_titolari")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\TitolariRepository")
 */
class Titolari
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
     * @ORM\Column(name="codice", type="integer")
     */
    private $codice;
		
		
    /**
     * @var string
     *
     * @ORM\Column(name="denominazione", type="string", length=255)
     */
    private $denominazione;
		
		
    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="string", length=255)
     */
    private $descrizione;

		


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
     * @return Titolari
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
     * Set denominazione
     *
     * @param string $denominazione
     *
     * @return Titolari
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
     * Set descrizione
     *
     * @param string $descrizione
     *
     * @return Titolari
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;

        return $this;
    }

    /**
     * Get descrizione
     *
     * @return string
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }
}
