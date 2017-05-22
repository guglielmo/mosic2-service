<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adempimenti
 *
 * @ORM\Table(name="msc_adempimenti")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\AdempimentiRepository")
 */
class Adempimenti
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
     * @ORM\Column(name="codice", type="integer", nullable=true)
     */
    private $codice;

     /**
     * @var int
     * @ORM\Column(name="progressivo", type="integer", nullable=true)
     */
    private $progressivo;

     /**
     * @var int
     * @ORM\Column(name="codice_scheda", type="integer", nullable=true)
     */
    private $codiceScheda;
	
	 /**
     * @var int
     * @ORM\Column(name="id_delibere", type="integer", nullable=true)
     */
    private $idDelibere;

   	/**
     * @var string
     * @ORM\Column(name="descrizione", type="string", length=255, nullable=true)
     */
    private $descrizione;
	
	/**
     * @var int
     * @ORM\Column(name="codice_descrizione", type="integer", nullable=true)
     */
    private $codiceDescrizione;
	
	/**
     * @var int
     * @ORM\Column(name="codice_fonte", type="integer", nullable=true)
     */
    private $codiceFonte;
	
	/**
     * @var int
     * @ORM\Column(name="codice_esito", type="integer", nullable=true)
     */
    private $codiceEsito;
	
	/**
     * @var \Date
     * @ORM\Column(name="data_scadenza", type="date", nullable=true)
     */
    private $dataScadenza;
	
	/**
     * @var int
     * @ORM\Column(name="giorni_scadenza", type="integer", nullable=true)
     */
    private $giorniScadenza;
	
	/**
     * @var int
     * @ORM\Column(name="mesi_scadenza", type="integer", nullable=true)
     */
    private $mesiScadenza;
	
	/**
     * @var int
     * @ORM\Column(name="anni_scadenza", type="integer", nullable=true)
     */
    private $anniScadenza;
	
	/**
     * @var int
     * @ORM\Column(name="vincolo", type="integer", nullable=true)
     */
    private $vincolo;
	
	/**
     * @var string
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;
	
	/**
     * @var int
     * @ORM\Column(name="utente", type="integer", nullable=true)
     */
    private $utente;
	
	/**
     * @var \DateTime
     * @ORM\Column(name="data_modifica", type="datetime")
     */
    private $dataModifica;


    public function __construct() {
        $this->dataModifica = new \DateTime();
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
     * @return Adempimenti
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
     * Set progressivo
     *
     * @param integer $progressivo
     *
     * @return Adempimenti
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
     * Set codiceScheda
     *
     * @param integer $codiceScheda
     *
     * @return Adempimenti
     */
    public function setCodiceScheda($codiceScheda)
    {
        $this->codiceScheda = $codiceScheda;

        return $this;
    }

    /**
     * Get codiceScheda
     *
     * @return integer
     */
    public function getCodiceScheda()
    {
        return $this->codiceScheda;
    }

    /**
     * Set idDelibere
     *
     * @param integer $idDelibere
     *
     * @return Adempimenti
     */
    public function setIdDelibere($idDelibere)
    {
        $this->idDelibere = $idDelibere;

        return $this;
    }

    /**
     * Get idDelibere
     *
     * @return integer
     */
    public function getIdDelibere()
    {
        return $this->idDelibere;
    }

    /**
     * Set descrizione
     *
     * @param string $descrizione
     *
     * @return Adempimenti
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

    /**
     * Set codiceDescrizione
     *
     * @param integer $codiceDescrizione
     *
     * @return Adempimenti
     */
    public function setCodiceDescrizione($codiceDescrizione)
    {
        $this->codiceDescrizione = $codiceDescrizione;

        return $this;
    }

    /**
     * Get codiceDescrizione
     *
     * @return integer
     */
    public function getCodiceDescrizione()
    {
        return $this->codiceDescrizione;
    }

    /**
     * Set codiceFonte
     *
     * @param integer $codiceFonte
     *
     * @return Adempimenti
     */
    public function setCodiceFonte($codiceFonte)
    {
        $this->codiceFonte = $codiceFonte;

        return $this;
    }

    /**
     * Get codiceFonte
     *
     * @return integer
     */
    public function getCodiceFonte()
    {
        return $this->codiceFonte;
    }

    /**
     * Set codiceEsito
     *
     * @param integer $codiceEsito
     *
     * @return Adempimenti
     */
    public function setCodiceEsito($codiceEsito)
    {
        $this->codiceEsito = $codiceEsito;

        return $this;
    }

    /**
     * Get codiceEsito
     *
     * @return integer
     */
    public function getCodiceEsito()
    {
        return $this->codiceEsito;
    }

    /**
     * Set dataScadenza
     *
     * @param \DateTime $dataScadenza
     *
     * @return Adempimenti
     */
    public function setDataScadenza($dataScadenza)
    {
        $this->dataScadenza = $dataScadenza;

        return $this;
    }

    /**
     * Get dataScadenza
     *
     * @return \DateTime
     */
    public function getDataScadenza()
    {
        return $this->dataScadenza;
    }

    /**
     * Set giorniScadenza
     *
     * @param integer $giorniScadenza
     *
     * @return Adempimenti
     */
    public function setGiorniScadenza($giorniScadenza)
    {
        $this->giorniScadenza = $giorniScadenza;

        return $this;
    }

    /**
     * Get giorniScadenza
     *
     * @return integer
     */
    public function getGiorniScadenza()
    {
        return $this->giorniScadenza;
    }

    /**
     * Set mesiScadenza
     *
     * @param integer $mesiScadenza
     *
     * @return Adempimenti
     */
    public function setMesiScadenza($mesiScadenza)
    {
        $this->mesiScadenza = $mesiScadenza;

        return $this;
    }

    /**
     * Get mesiScadenza
     *
     * @return integer
     */
    public function getMesiScadenza()
    {
        return $this->mesiScadenza;
    }

    /**
     * Set anniScadenza
     *
     * @param integer $anniScadenza
     *
     * @return Adempimenti
     */
    public function setAnniScadenza($anniScadenza)
    {
        $this->anniScadenza = $anniScadenza;

        return $this;
    }

    /**
     * Get anniScadenza
     *
     * @return integer
     */
    public function getAnniScadenza()
    {
        return $this->anniScadenza;
    }

    /**
     * Set vincolo
     *
     * @param integer $vincolo
     *
     * @return Adempimenti
     */
    public function setVincolo($vincolo)
    {
        $this->vincolo = $vincolo;

        return $this;
    }

    /**
     * Get vincolo
     *
     * @return integer
     */
    public function getVincolo()
    {
        return $this->vincolo;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Adempimenti
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set utente
     *
     * @param integer $utente
     *
     * @return Adempimenti
     */
    public function setUtente($utente)
    {
        $this->utente = $utente;

        return $this;
    }

    /**
     * Get utente
     *
     * @return integer
     */
    public function getUtente()
    {
        return $this->utente;
    }

    /**
     * Set dataModifica
     *
     * @param \DateTime $dataModifica
     *
     * @return Adempimenti
     */
    public function setDataModifica($dataModifica)
    {
        $this->dataModifica = $dataModifica;

        return $this;
    }

    /**
     * Get dataModifica
     *
     * @return \DateTime
     */
    public function getDataModifica()
    {
        return $this->dataModifica;
    }
}
