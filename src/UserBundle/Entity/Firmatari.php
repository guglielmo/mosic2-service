<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Firmatari
 *
 * @ORM\Table(name="msc_firmatari")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\FirmatariRepository")
 */
class Firmatari
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
     * @ORM\Column(name="chiave", type="integer")
     */
    private $chiave;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo;


    /**
     * @var string
     *
     * @ORM\Column(name="denominazione", type="string", length=255)
     */
    private $denominazione;

    /**
     * @var string
     *
     * @ORM\Column(name="denominazione_estesa", type="string", length=255)
     */
    private $denominazioneEstesa;

    /**
     * @var string
     *
     * @ORM\Column(name="disattivato", type="integer")
     */
    private $disattivato;



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
     * Set chiave
     *
     * @param integer $chiave
     *
     * @return Firmatari
     */
    public function setChiave($chiave)
    {
        $this->chiave = $chiave;

        return $this;
    }

    /**
     * Get chiave
     *
     * @return integer
     */
    public function getChiave()
    {
        return $this->chiave;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return Firmatari
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set denominazione
     *
     * @param string $denominazione
     *
     * @return Firmatari
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
     * Set denominazioneEstesa
     *
     * @param string $denominazioneEstesa
     *
     * @return Firmatari
     */
    public function setDenominazioneEstesa($denominazioneEstesa)
    {
        $this->denominazioneEstesa = $denominazioneEstesa;

        return $this;
    }

    /**
     * Get denominazioneEstesa
     *
     * @return string
     */
    public function getDenominazioneEstesa()
    {
        return $this->denominazioneEstesa;
    }

    /**
     * Set disattivato
     *
     * @param integer $disattivato
     *
     * @return Firmatari
     */
    public function setDisattivato($disattivato)
    {
        $this->disattivato = $disattivato;

        return $this;
    }

    /**
     * Get disattivato
     *
     * @return integer
     */
    public function getDisattivato()
    {
        return $this->disattivato;
    }
}
