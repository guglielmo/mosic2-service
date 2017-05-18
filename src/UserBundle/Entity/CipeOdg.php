<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CipeOdg
 *
 * @ORM\Table(name="msc_cipe_ordini")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\CipeOdgRepository")
 */
class CipeOdg
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
     * @ORM\Column(name="id_cipe", type="integer")
     */
    private $idCipe;

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
     * @ORM\Column(name="id_sottofascicoli", type="integer")
     */
    private $idSottoFascicoli;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_argomenti", type="integer")
     */
    private $idArgomenti;

    /**
     * @var \int
     *
     * @ORM\Column(name="tipo_argomenti", type="integer")
     */
    private $TipoArgomenti;


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
     * @var \int
     *
     * @ORM\Column(name="id_esito", type="integer")
     */
    private $idEsito;

    /**
     * @var \int
     *
     * @ORM\Column(name="tipo_esito", type="integer")
     */
    private $tipoEsito;

    /**
     * @var \int
     *
     * @ORM\Column(name="id_delibera", type="integer")
     */
    private $idDelibera;

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
     * Set idCipe
     *
     * @param integer $idCipe
     *
     * @return CipeOdg
     */
    public function setIdCipe($idCipe)
    {
        $this->idCipe = $idCipe;

        return $this;
    }

    /**
     * Get idCipe
     *
     * @return integer
     */
    public function getIdCipe()
    {
        return $this->idCipe;
    }

    /**
     * Set progressivo
     *
     * @param integer $progressivo
     *
     * @return CipeOdg
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
     * @return CipeOdg
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
     * @return CipeOdg
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
     * Set idSottoFascicoli
     *
     * @param integer $idSottoFascicoli
     *
     * @return CipeOdg
     */
    public function setIdSottoFascicoli($idSottoFascicoli)
    {
        $this->idSottoFascicoli = $idSottoFascicoli;

        return $this;
    }

    /**
     * Get idSottoFascicoli
     *
     * @return integer
     */
    public function getIdSottoFascicoli()
    {
        return $this->idSottoFascicoli;
    }

    /**
     * Set idArgomenti
     *
     * @param integer $idArgomenti
     *
     * @return CipeOdg
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
     * Set tipoArgomenti
     *
     * @param integer $tipoArgomenti
     *
     * @return CipeOdg
     */
    public function setTipoArgomenti($tipoArgomenti)
    {
        $this->TipoArgomenti = $tipoArgomenti;

        return $this;
    }

    /**
     * Get tipoArgomenti
     *
     * @return integer
     */
    public function getTipoArgomenti()
    {
        return $this->TipoArgomenti;
    }

    /**
     * Set idUffici
     *
     * @param integer $idUffici
     *
     * @return CipeOdg
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
     * @return CipeOdg
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

    /**
     * Set denominazione
     *
     * @param string $denominazione
     *
     * @return CipeOdg
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
     * @return CipeOdg
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
     * Set idEsito
     *
     * @param integer $idEsito
     *
     * @return CipeOdg
     */
    public function setIdEsito($idEsito)
    {
        $this->idEsito = $idEsito;

        return $this;
    }

    /**
     * Get idEsito
     *
     * @return integer
     */
    public function getIdEsito()
    {
        return $this->idEsito;
    }

    /**
     * Set tipoEsito
     *
     * @param integer $tipoEsito
     *
     * @return CipeOdg
     */
    public function setTipoEsito($tipoEsito)
    {
        $this->tipoEsito = $tipoEsito;

        return $this;
    }

    /**
     * Get tipoEsito
     *
     * @return integer
     */
    public function getTipoEsito()
    {
        return $this->tipoEsito;
    }

    /**
     * Set idDelibera
     *
     * @param integer $idDelibera
     *
     * @return CipeOdg
     */
    public function setIdDelibera($idDelibera)
    {
        $this->idDelibera = $idDelibera;

        return $this;
    }

    /**
     * Get idDelibera
     *
     * @return integer
     */
    public function getIdDelibera()
    {
        return $this->idDelibera;
    }

    /**
     * Set annotazioni
     *
     * @param string $annotazioni
     *
     * @return CipeOdg
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
     * Set stato
     *
     * @param integer $stato
     *
     * @return CipeOdg
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
}
