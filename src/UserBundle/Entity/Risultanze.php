<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Risultanze
 *
 * @ORM\Table(name="msc_risultanze")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RisultanzeRepository")
 */
class Risultanze
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
     * Set codice
     *
     * @param integer $codice
     *
     * @return Risultanze
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
     * @return Risultanze
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
}
