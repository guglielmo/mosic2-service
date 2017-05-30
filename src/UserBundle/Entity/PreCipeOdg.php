<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PreCipeOdg
 *
 * @ORM\Table(name="msc_precipe_ordini")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\PreCipeOdgRepository")
 */
class PreCipeOdg
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
     * @var \int
     *
     * @ORM\Column(name="id_precipe", type="integer")
     */
    private $idPreCipe;

    /**
     * @var \int
     *
     * @ORM\Column(name="progressivo", type="integer")
     */
    private $progressivo;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_titolari", type="integer")
     */
    private $idTitolari;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_fascicoli", type="integer")
     */
    private $idFascicoli;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_argomenti", type="integer")
     */
    private $idArgomenti;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_uffici", type="integer")
     */
    private $idUffici;

    /**
     * @var string
     *
     * @ORM\Column(name="ordine", type="string", length=255)
     */
    private $ordine;

    /**
     * @var text
     *
     * @ORM\Column(name="denominazione", type="text")
     */
    private $denominazione;

    /**
     * @var \int
     *
     * @ORM\Column(name="risultanza", type="integer")
     */
    private $risultanza;

    /**
     * @var text
     *
     * @ORM\Column(name="annotazioni", type="text")
     */
    private $annotazioni;


    /**
     * @var \int
     *
     * @ORM\Column(name="stato", type="integer")
     */
    private $stato;


    public function __construct()
    {
        $this->idUffici = 0;
        $this->idArgomenti = 0;
        $this->progressivo = 0;
        $this->risultanza = 0;
        $this->stato = 0;
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
     * Set progressivo
     *
     * @param integer $progressivo
     *
     * @return PreCipeOdg
     */
    public function setProgressivo($progressivo)
    {
        $this->progressivo = $progressivo;

        return $this;
    }

    /**
     * Get progressivo
     *
     * @return integer
     */
    public function getProgressivo()
    {
        return $this->progressivo;
    }

    /**
     * Set idTitolari
     *
     * @param integer $idTitolari
     *
     * @return PreCipeOdg
     */
    public function setIdTitolari($idTitolari)
    {
        $this->idTitolari = $idTitolari;

        return $this;
    }

    /**
     * Get idTitolari
     *
     * @return integer
     */
    public function getIdTitolari()
    {
        return $this->idTitolari;
    }

    /**
     * Set idFascicoli
     *
     * @param integer $idFascicoli
     *
     * @return PreCipeOdg
     */
    public function setIdFascicoli($idFascicoli)
    {
        $this->idFascicoli = $idFascicoli;

        return $this;
    }

    /**
     * Get idFascicoli
     *
     * @return integer
     */
    public function getIdFascicoli()
    {
        return $this->idFascicoli;
    }

    /**
     * Set idArgomenti
     *
     * @param integer $idArgomenti
     *
     * @return PreCipeOdg
     */
    public function setIdArgomenti($idArgomenti)
    {
        $this->idArgomenti = $idArgomenti;

        return $this;
    }

    /**
     * Get idArgomenti
     *
     * @return integer
     */
    public function getIdArgomenti()
    {
        return $this->idArgomenti;
    }

    /**
     * Set idUffici
     *
     * @param integer $idUffici
     *
     * @return PreCipeOdg
     */
    public function setIdUffici($idUffici)
    {
        $this->idUffici = $idUffici;

        return $this;
    }

    /**
     * Get idUffici
     *
     * @return integer
     */
    public function getIdUffici()
    {
        return $this->idUffici;
    }

    /**
     * Set ordine
     *
     * @param string $ordine
     *
     * @return PreCipeOdg
     */
    public function setNumeroOdg($ordine)
    {
        $this->ordine = $ordine;

        return $this;
    }

    /**
     * Get ordine
     *
     * @return string
     */
    public function getNumeroOdg()
    {
        return $this->ordine;
    }

    /**
     * Set denominazione
     *
     * @param string $denominazione
     *
     * @return PreCipeOdg
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
     * Set risultanza
     *
     * @param integer $risultanza
     *
     * @return PreCipeOdg
     */
    public function setRisultanza($risultanza)
    {
        $this->risultanza = $risultanza;

        return $this;
    }

    /**
     * Get risultanza
     *
     * @return integer
     */
    public function getRisultanza()
    {
        return $this->risultanza;
    }

    /**
     * Set annotazioni
     *
     * @param string $annotazioni
     *
     * @return PreCipeOdg
     */
    public function setAnnotazioni($annotazioni)
    {
        $this->annotazioni = $annotazioni;

        return $this;
    }

    /**
     * Get annotazioni
     *
     * @return string
     */
    public function getAnnotazioni()
    {
        return $this->annotazioni;
    }

    /**
     * Set idPreCipe
     *
     * @param integer $idPreCipe
     *
     * @return PreCipeOdg
     */
    public function setIdPreCipe($idPreCipe)
    {
        $this->idPreCipe = $idPreCipe;

        return $this;
    }

    /**
     * Get idPreCipe
     *
     * @return integer
     */
    public function getIdPreCipe()
    {
        return $this->idPreCipe;
    }

    /**
     * Set stato
     *
     * @param integer $stato
     *
     * @return PreCipeOdg
     */
    public function setStato($stato)
    {
        $this->stato = $stato;

        return $this;
    }

    /**
     * Get stato
     *
     * @return integer
     */
    public function getStato()
    {
        return $this->stato;
    }

    /**
     * Set ordine
     *
     * @param string $ordine
     *
     * @return PreCipeOdg
     */
    public function setOrdine($ordine)
    {
        $this->ordine = $ordine;

        return $this;
    }

    /**
     * Get ordine
     *
     * @return string
     */
    public function getOrdine()
    {
        return $this->ordine;
    }
}
