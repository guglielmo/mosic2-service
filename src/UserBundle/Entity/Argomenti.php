<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Argomenti
 *
 * @ORM\Table(name="msc_argomenti")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\ArgomentiRepository")
 */
class Argomenti
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set denominazione
     *
     * @param string $denominazione
     *
     * @return Argomenti
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
     * Set codice
     *
     * @param integer $codice
     *
     * @return Argomenti
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
}
