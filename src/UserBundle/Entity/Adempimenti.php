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
     * @var string
     * @ORM\Column(name="istruttore", type="text", nullable=true)
     */
    private $istruttore;

    /**
     * @var int
     * @ORM\Column(name="id_delibere", type="integer", nullable=true)
     */
    private $idDelibere;

    /**
     * @var int
     * @ORM\Column(name="numero_delibera", type="integer", nullable=true)
     */
    private $numeroDelibera;

    /**
     * @var int
     * @ORM\Column(name="anno", type="integer", nullable=true)
     */
    private $anno;

    /**
     * @var \Date
     * @ORM\Column(name="seduta", type="date", nullable=true)
     */
    private $seduta;

    /**
     * @var string
     * @ORM\Column(name="materia", type="text", nullable=true)
     */
    private $materia;

    /**
     * @var string
     * @ORM\Column(name="argomento", type="text", nullable=true)
     */
    private $argomento;

    /**
     * @var string
     * @ORM\Column(name="fondo_norma", type="text", nullable=true)
     */
    private $fondoNorma;

    /**
     * @var int
     * @ORM\Column(name="ambito", type="integer", nullable=true)
     */
    private $ambito;

    /**
     * @var string
     * @ORM\Column(name="localizzazione", type="text", nullable=true)
     */
    private $localizzazione;

    /**
     * @var string
     * @ORM\Column(name="cup", type="text", nullable=true)
     */
    private $cup;

    /**
     * @var string
     * @ORM\Column(name="riferimento", type="text", nullable=true)
     */
    private $riferimento;

    /**
     * @var string
     * @ORM\Column(name="descrizione", type="text", nullable=true)
     */
    private $descrizione;

    /**
     * @var int
     * @ORM\Column(name="tipologia", type="integer", nullable=true)
     */
    private $tipologia;

    /**
     * @var int
     * @ORM\Column(name="azione", type="integer", nullable=true)
     */
    private $azione;

    /**
     * @var string
     * @ORM\Column(name="mancato_assolvimento", type="text", nullable=true)
     */
    private $mancatoAssolvimento;

    /**
     * @var string
     * @ORM\Column(name="norme_delibere", type="text", nullable=true)
     */
    private $normeDelibere;

    /**
     * @var \Date
     * @ORM\Column(name="data_scadenza", type="date", nullable=true)
     */
    private $dataScadenza;

    /**
     * @var string
     * @ORM\Column(name="destinatario", type="text", nullable=true)
     */
    private $destinatario;

    /**
     * @var string
     * @ORM\Column(name="struttura", type="text", nullable=true)
     */
    private $struttura;

    /**
     * @var string
     * @ORM\Column(name="adempiuto", type="text", nullable=true)
     */
    private $adempiuto;

    /**
     * @var int
     * @ORM\Column(name="periodicita", type="integer", nullable=true)
     */
    private $periodicita;

    /**
     * @var int
     * @ORM\Column(name="pluriennalita", type="integer", nullable=true)
     */
    private $pluriennalita;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var int
     * @ORM\Column(name="superato", type="integer")
     */
    private $superato;


    /**
     * @var int
     * @ORM\Column(name="progressivo", type="integer")
     */
    private $progressivo;


    public function __construct() {
        $this->seduta = new \DateTime();
        $this->dataScadenza = new \DateTime();
        $this->superato = 0;
        $this->progressivo = 0;
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
     * Set istruttore
     *
     * @param string $istruttore
     *
     * @return Adempimenti
     */
    public function setIstruttore($istruttore)
    {
        $this->istruttore = $istruttore;

        return $this;
    }

    /**
     * Get istruttore
     *
     * @return string
     */
    public function getIstruttore()
    {
        return $this->istruttore;
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
     * Set numeroDelibera
     *
     * @param integer $numeroDelibera
     *
     * @return Adempimenti
     */
    public function setNumeroDelibera($numeroDelibera)
    {
        $this->numeroDelibera = $numeroDelibera;

        return $this;
    }

    /**
     * Get numeroDelibera
     *
     * @return integer
     */
    public function getNumeroDelibera()
    {
        return $this->numeroDelibera;
    }

    /**
     * Set anno
     *
     * @param integer $anno
     *
     * @return Adempimenti
     */
    public function setAnno($anno)
    {
        $this->anno = $anno;

        return $this;
    }

    /**
     * Get anno
     *
     * @return integer
     */
    public function getAnno()
    {
        return $this->anno;
    }

    /**
     * Set seduta
     *
     * @param \DateTime $seduta
     *
     * @return Adempimenti
     */
    public function setSeduta($seduta)
    {
        $this->seduta = $seduta;

        return $this;
    }

    /**
     * Get seduta
     *
     * @return \DateTime
     */
    public function getSeduta()
    {
        return $this->seduta;
    }

    /**
     * Set materia
     *
     * @param string $materia
     *
     * @return Adempimenti
     */
    public function setMateria($materia)
    {
        $this->materia = $materia;

        return $this;
    }

    /**
     * Get materia
     *
     * @return string
     */
    public function getMateria()
    {
        return $this->materia;
    }

    /**
     * Set argomento
     *
     * @param string $argomento
     *
     * @return Adempimenti
     */
    public function setArgomento($argomento)
    {
        $this->argomento = $argomento;

        return $this;
    }

    /**
     * Get argomento
     *
     * @return string
     */
    public function getArgomento()
    {
        return $this->argomento;
    }

    /**
     * Set fondoNorma
     *
     * @param string $fondoNorma
     *
     * @return Adempimenti
     */
    public function setFondoNorma($fondoNorma)
    {
        $this->fondoNorma = $fondoNorma;

        return $this;
    }

    /**
     * Get fondoNorma
     *
     * @return string
     */
    public function getFondoNorma()
    {
        return $this->fondoNorma;
    }

    /**
     * Set ambito
     *
     * @param integer $ambito
     *
     * @return Adempimenti
     */
    public function setAmbito($ambito)
    {
        $this->ambito = $ambito;

        return $this;
    }

    /**
     * Get ambito
     *
     * @return integer
     */
    public function getAmbito()
    {
        return $this->ambito;
    }

    /**
     * Set localizzazione
     *
     * @param string $localizzazione
     *
     * @return Adempimenti
     */
    public function setLocalizzazione($localizzazione)
    {
        $this->localizzazione = $localizzazione;

        return $this;
    }

    /**
     * Get localizzazione
     *
     * @return string
     */
    public function getLocalizzazione()
    {
        return $this->localizzazione;
    }

    /**
     * Set cup
     *
     * @param string $cup
     *
     * @return Adempimenti
     */
    public function setCup($cup)
    {
        $this->cup = $cup;

        return $this;
    }

    /**
     * Get cup
     *
     * @return string
     */
    public function getCup()
    {
        return $this->cup;
    }

    /**
     * Set riferimento
     *
     * @param string $riferimento
     *
     * @return Adempimenti
     */
    public function setRiferimento($riferimento)
    {
        $this->riferimento = $riferimento;

        return $this;
    }

    /**
     * Get riferimento
     *
     * @return string
     */
    public function getRiferimento()
    {
        return $this->riferimento;
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
     * Set tipologia
     *
     * @param integer $tipologia
     *
     * @return Adempimenti
     */
    public function setTipologia($tipologia)
    {
        $this->tipologia = $tipologia;

        return $this;
    }

    /**
     * Get tipologia
     *
     * @return integer
     */
    public function getTipologia()
    {
        return $this->tipologia;
    }

    /**
     * Set azione
     *
     * @param integer $azione
     *
     * @return Adempimenti
     */
    public function setAzione($azione)
    {
        $this->azione = $azione;

        return $this;
    }

    /**
     * Get azione
     *
     * @return integer
     */
    public function getAzione()
    {
        return $this->azione;
    }

    /**
     * Set mancatoAssolvimento
     *
     * @param string $mancatoAssolvimento
     *
     * @return Adempimenti
     */
    public function setMancatoAssolvimento($mancatoAssolvimento)
    {
        $this->mancatoAssolvimento = $mancatoAssolvimento;

        return $this;
    }

    /**
     * Get mancatoAssolvimento
     *
     * @return string
     */
    public function getMancatoAssolvimento()
    {
        return $this->mancatoAssolvimento;
    }

    /**
     * Set normeDelibere
     *
     * @param string $normeDelibere
     *
     * @return Adempimenti
     */
    public function setNormeDelibere($normeDelibere)
    {
        $this->normeDelibere = $normeDelibere;

        return $this;
    }

    /**
     * Get normeDelibere
     *
     * @return string
     */
    public function getNormeDelibere()
    {
        return $this->normeDelibere;
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
     * Set destinatario
     *
     * @param string $destinatario
     *
     * @return Adempimenti
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    /**
     * Get destinatario
     *
     * @return string
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set struttura
     *
     * @param string $struttura
     *
     * @return Adempimenti
     */
    public function setStruttura($struttura)
    {
        $this->struttura = $struttura;

        return $this;
    }

    /**
     * Get struttura
     *
     * @return string
     */
    public function getStruttura()
    {
        return $this->struttura;
    }

    /**
     * Set adempiuto
     *
     * @param string $adempiuto
     *
     * @return Adempimenti
     */
    public function setAdempiuto($adempiuto)
    {
        $this->adempiuto = $adempiuto;

        return $this;
    }

    /**
     * Get adempiuto
     *
     * @return string
     */
    public function getAdempiuto()
    {
        return $this->adempiuto;
    }

    /**
     * Set periodicita
     *
     * @param integer $periodicita
     *
     * @return Adempimenti
     */
    public function setPeriodicita($periodicita)
    {
        $this->periodicita = $periodicita;

        return $this;
    }

    /**
     * Get periodicita
     *
     * @return integer
     */
    public function getPeriodicita()
    {
        return $this->periodicita;
    }

    /**
     * Set pluriennalita
     *
     * @param integer $pluriennalita
     *
     * @return Adempimenti
     */
    public function setPluriennalita($pluriennalita)
    {
        $this->pluriennalita = $pluriennalita;

        return $this;
    }

    /**
     * Get pluriennalita
     *
     * @return integer
     */
    public function getPluriennalita()
    {
        return $this->pluriennalita;
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
     * Set superato
     *
     * @param integer $superato
     *
     * @return Adempimenti
     */
    public function setSuperato($superato)
    {
        $this->superato = $superato;

        return $this;
    }

    /**
     * Get superato
     *
     * @return integer
     */
    public function getSuperato()
    {
        return $this->superato;
    }

    /**
     * @return int
     */
    public function getProgressivo()
    {
        return $this->progressivo;
    }

    /**
     * @param int $progressivo
     */
    public function setProgressivo($progressivo)
    {
        $this->progressivo = $progressivo;
    }



}
