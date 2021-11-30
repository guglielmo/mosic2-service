<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
/**
 * Amministrazione
 *
 * @ORM\Table(name="msc_amministrazioni")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\AmministrazioneRepository")
 */
class Amministrazione
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="RelAmministrazioniFascicoli", mappedBy="idAmministrazioni", cascade={"all"})
     */
    private $id;

		
    /**
     * @var string
     *
     * @ORM\Column(name="codice", type="string", length=255)
     */
    private $codice;
		
		
    /**
     * @var string
     *
     * @ORM\Column(name="denominazione", type="string", length=255)
     */
    private $denominazione;

    //MODIFICA MOSIC 3.0 del 17/06/2020
    /**
     * @var int
     *
     * @ORM\Column(name="disattivo", type="integer")
     */
    private $disattivo;



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
     * @param string $codice
     *
     * @return Amministrazione
     */
    public function setCodice($codice)
    {
        $this->codice = $codice;

        return $this;
    }

    /**
     * Get codice
     *
     * @return string
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
     * @return Amministrazione
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
     * @return int
     */
    public function getDisattivo()
    {
        return $this->disattivo;
    }

    /**
     * @param int $disattivo
     */
    public function setDisattivo($disattivo)
    {
        $this->disattivo = $disattivo;
    }



}
