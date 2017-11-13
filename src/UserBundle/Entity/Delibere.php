<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Delibere
 *
 * @ORM\Table(name="msc_delibere")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\DelibereRepository")
 */
class Delibere
{
    /** ########################################################## DELIBERA #####################################
     *  #########################################################################################################
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Date
     * @ORM\Column(name="data", type="date", nullable=true)
     */
    private $data;

    /**
     * @var int
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var int
     * @ORM\Column(name="id_stato", type="integer", nullable=true)
     */
    private $idStato;

    /**
     * @var int
     * @ORM\Column(name="id_tipologia", type="integer", nullable=true)
     */
    private $idTipologia;

    /**
     * @var string
     * @ORM\Column(name="argomento", type="string", length=255, nullable=true)
     */
    private $argomento;

    /**
     * @var string
     * @ORM\Column(name="finanziamento", type="string", length=255, nullable=true)
     */
    private $finanziamento;

    /**
     * @var string
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @var string
     * @ORM\Column(name="note_servizio", type="string", length=255, nullable=true)
     */
    private $noteServizio;



    /** ########################################################## FIRMA ########################################
     *  #########################################################################################################
     *  ################################ Aquisizione
     * @var int
     * @ORM\Column(name="scheda", type="integer", nullable=true)
     */
    private $scheda;
    /**
     * @var \Date
     * @ORM\Column(name="data_consegna", type="date", nullable=true)
     */
    private $dataConsegna;
    /**
     * @var string
     * @ORM\Column(name="note_firma", type="string", length=255, nullable=true)
     */
    private $noteFirma;

    /**
     * ################################ Direttore
     * @var int
     * @ORM\Column(name="id_direttore", type="integer", nullable=true)
     */
    private $idDirettore;
    /**
     * @var \Date
     * @ORM\Column(name="data_direttore_invio", type="date", nullable=true)
     */
    private $dataDirettoreInvio;
    /**
     * @var \Date
     * @ORM\Column(name="data_direttore_ritorno", type="date", nullable=true)
     */
    private $dataDirettoreRitorno;
    /**
     * @var string
     * @ORM\Column(name="note_direttore", type="string", length=255, nullable=true)
     */
    private $noteDirettore;

    /**
     * ################################ Mef
     * @var int
     * @ORM\Column(name="invio_mef", type="integer", nullable=true)
     */
    private $invioMef;
    /**
     * @var \Date
     * @ORM\Column(name="data_mef_invio", type="date", nullable=true)
     */
    private $dataMefInvio;
    /**
     * @var \Date
     * @ORM\Column(name="data_mef_pec", type="date", nullable=true)
     */
    private $dataMefPec;
    /**
     * @var \Date
     * @ORM\Column(name="data_mef_ritorno", type="date", nullable=true)
     */
    private $dataMefRitorno;
    /**
     * @var string
     * @ORM\Column(name="note_mef", type="string", length=255, nullable=true)
     */
    private $noteMef;

    /**
     * ################################ Segretario
     * @var int
     * @ORM\Column(name="id_segretario", type="integer", nullable=true)
     */
    private $idSegretario;
    /**
     * @var \Date
     * @ORM\Column(name="data_segretario_invio", type="date", nullable=true)
     */
    private $dataSegretarioInvio;
    /**
     * @var \Date
     * @ORM\Column(name="data_segretario_ritorno", type="date", nullable=true)
     */
    private $dataSegretarioRitorno;
    /**
     * @var string
     * @ORM\Column(name="note_segretario", type="string", length=255, nullable=true)
     */
    private $noteSegretario;

    /**
     * ################################ Presidente
     * @var int
     * @ORM\Column(name="id_presidente", type="integer", nullable=true)
     */
    private $idPresidente;
    /**
     * @var \Date
     * @ORM\Column(name="data_presidente_invio", type="date", nullable=true)
     */
    private $dataPresidenteInvio;
    /**
     * @var \Date
     * @ORM\Column(name="data_presidente_ritorno", type="date", nullable=true)
     */
    private $dataPresidenteRitorno;
    /**
     * @var string
     * @ORM\Column(name="note_presidente", type="string", length=255, nullable=true)
     */
    private $notePresidente;



    /** ########################################################## CORTE DEI CONTI ##############################
     *  #########################################################################################################
     *  ################################ Registrazione Corte dei Conti
    /**
     * @var \Date
     * @ORM\Column(name="data_invio_cc", type="date", nullable=true)
     */
    private $dataInvioCC;
    /**
     * @var int
     * @ORM\Column(name="numero_cc", type="integer", nullable=true)
     */
    private $numeroCC;
    /**
     * @var int
     * @ORM\Column(name="invio_ragioneria_cc", type="integer", nullable=true)
     */
    private $invioRagioneriaCC;
    /**
     * @var \Date
     * @ORM\Column(name="data_registrazione_cc", type="date", nullable=true)
     */
    private $dataRegistrazioneCC;
    /**
     * @var int
     * @ORM\Column(name="id_registro_cc", type="integer", nullable=true)
     */
    private $idRegistroCC;
    /**
     * @var int
     * @ORM\Column(name="foglio_cc", type="integer", nullable=true)
     */
    private $foglioCC;
    /**
     * @var int
     * @ORM\Column(name="tipo_registrazione_cc", type="integer", nullable=true)
     */
    private $tipoRegistrazioneCC;

    /**
     * @var string
     * @ORM\Column(name="note_cc", type="string", length=255, nullable=true)
     */
    private $noteCC;



    /** ########################################################## ALTRE ISTITUZIONI ############################
     *  #########################################################################################################
     *  ################################ Conferenza Stato-Regioni
     * @var \Date
     * @ORM\Column(name="data_invio_cst", type="date", nullable=true)
     */
    private $dataInvioCST;
    /**
     * @var int
     * @ORM\Column(name="numero_invio_cst", type="integer", nullable=true)
     */
    private $numeroInvioCST;
    /**
     * @var string
     * @ORM\Column(name="note_invio_cst", type="string", length=255, nullable=true)
     */
    private $noteInvioCST;
    /**
     * @var \Date
     * @ORM\Column(name="data_ritorno_cst", type="date", nullable=true)
     */
    private $dataRitornoCST;
    /**
     * @var int
     * @ORM\Column(name="numero_ritorno_cst", type="integer", nullable=true)
     */
    private $numeroRitornoCST;
    /**
     * @var string
     * @ORM\Column(name="note_ritorno_cst", type="string", length=255, nullable=true)
     */
    private $noteRitornoCST;
    
    /**
     *  ################################ Conferenza unificata
     * @var \Date
     * @ORM\Column(name="data_invio_cu", type="date", nullable=true)
     */
    private $dataInvioCU;
    /**
     * @var int
     * @ORM\Column(name="numero_invio_cu", type="integer", nullable=true)
     */
    private $numeroInvioCU;
    /**
     * @var string
     * @ORM\Column(name="note_invio_cu", type="string", length=255, nullable=true)
     */
    private $noteInvioCU;
    /**
     * @var \Date
     * @ORM\Column(name="data_ritorno_cu", type="date", nullable=true)
     */
    private $dataRitornoCU;
    /**
     * @var int
     * @ORM\Column(name="numero_ritorno_cu", type="integer", nullable=true)
     */
    private $numeroRitornoCU;
    /**
     * @var string
     * @ORM\Column(name="note_ritorno_cu", type="string", length=255, nullable=true)
     */
    private $noteRitornoCU;

    /**
     *  ################################ Commissioni parlamentari (Camera / Senato)
     * @var \Date
     * @ORM\Column(name="data_invio_cp", type="date", nullable=true)
     */
    private $dataInvioCP;
    /**
     * @var int
     * @ORM\Column(name="numero_invio_cp", type="integer", nullable=true)
     */
    private $numeroInvioCP;
    /**
     * @var string
     * @ORM\Column(name="note_invio_cp", type="string", length=255, nullable=true)
     */
    private $noteInvioCP;
    /**
     * @var \Date
     * @ORM\Column(name="data_ritorno_cp", type="date", nullable=true)
     */
    private $dataRitornoCP;
    /**
     * @var int
     * @ORM\Column(name="numero_ritorno_cp", type="integer", nullable=true)
     */
    private $numeroRitornoCP;
    /**
     * @var string
     * @ORM\Column(name="note_ritorno_cp", type="string", length=255, nullable=true)
     */
    private $noteRitornoCP;

    /**
     *  ################################ Parlamento
     * @var \Date
     * @ORM\Column(name="data_invio_p", type="date", nullable=true)
     */
    private $dataInvioP;
    /**
     * @var string
     * @ORM\Column(name="note_p", type="string", length=255, nullable=true)
     */
    private $noteP;



    /** ########################################################## GAZZETTA UFFICIALE ###########################
     *  #########################################################################################################
     * @var \Date
     * @ORM\Column(name="data_invio_gu", type="date", nullable=true)
     */
    private $dataInvioGU;
    /**
     * @var int
     * @ORM\Column(name="numero_invio_gu", type="integer", nullable=true)
     */
    private $numeroInvioGU;
    /**
     * @var int
     * @ORM\Column(name="tipo_gu", type="integer", nullable=true)
     */
    private $tipoGU;
    /**
     * @var \Date
     * @ORM\Column(name="data_gu", type="date", nullable=true)
     */
    private $dataGU;
    /**
     * @var int
     * @ORM\Column(name="numero_gu", type="integer", nullable=true)
     */
    private $numeroGU;
    /**
     * @var \Date
     * @ORM\Column(name="data_ec_gu", type="date", nullable=true)
     */
    private $dataEcGU;
    /**
     * @var int
     * @ORM\Column(name="numero_ec_gu", type="integer", nullable=true)
     */
    private $numeroEcGU;
    /**
     * @var \Date
     * @ORM\Column(name="data_co_gu", type="date", nullable=true)
     */
    private $dataCoGU;
    /**
     * @var int
     * @ORM\Column(name="numero_co_gu", type="integer", nullable=true)
     */
    private $numeroCoGU;
    /**
     * @var int
     * @ORM\Column(name="pubblicazione_gu", type="integer", nullable=true)
     */
    private $pubblicazioneGU;
    /**
     * @var string
     * @ORM\Column(name="note_gu", type="string", length=255, nullable=true)
     */
    private $noteGU;

    /**
     * @var int
     * @ORM\Column(name="situazione", type="integer", nullable=true)
     */
    private $situazione;
    


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
     * Set data
     *
     * @param \DateTime $data
     *
     * @return Delibere
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Delibere
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set idStato
     *
     * @param integer $idStato
     *
     * @return Delibere
     */
    public function setIdStato($idStato)
    {
        $this->idStato = $idStato;

        return $this;
    }

    /**
     * Get idStato
     *
     * @return integer
     */
    public function getIdStato()
    {
        return $this->idStato;
    }

    /**
     * Set idTipologia
     *
     * @param integer $idTipologia
     *
     * @return Delibere
     */
    public function setIdTipologia($idTipologia)
    {
        $this->idTipologia = $idTipologia;

        return $this;
    }

    /**
     * Get idTipologia
     *
     * @return integer
     */
    public function getIdTipologia()
    {
        return $this->idTipologia;
    }

    /**
     * Set argomento
     *
     * @param string $argomento
     *
     * @return Delibere
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
     * Set finanziamento
     *
     * @param string $finanziamento
     *
     * @return Delibere
     */
    public function setFinanziamento($finanziamento)
    {
        $this->finanziamento = $finanziamento;

        return $this;
    }

    /**
     * Get finanziamento
     *
     * @return string
     */
    public function getFinanziamento()
    {
        return $this->finanziamento;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Delibere
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
     * Set noteServizio
     *
     * @param string $noteServizio
     *
     * @return Delibere
     */
    public function setNoteServizio($noteServizio)
    {
        $this->noteServizio = $noteServizio;

        return $this;
    }

    /**
     * Get noteServizio
     *
     * @return string
     */
    public function getNoteServizio()
    {
        return $this->noteServizio;
    }

    /**
     * Set scheda
     *
     * @param integer $scheda
     *
     * @return Delibere
     */
    public function setScheda($scheda)
    {
        $this->scheda = $scheda;

        return $this;
    }

    /**
     * Get scheda
     *
     * @return integer
     */
    public function getScheda()
    {
        return $this->scheda;
    }

    /**
     * Set dataConsegna
     *
     * @param \DateTime $dataConsegna
     *
     * @return Delibere
     */
    public function setDataConsegna($dataConsegna)
    {
        $this->dataConsegna = $dataConsegna;

        return $this;
    }

    /**
     * Get dataConsegna
     *
     * @return \DateTime
     */
    public function getDataConsegna()
    {
        return $this->dataConsegna;
    }

    /**
     * Set noteFirma
     *
     * @param string $noteFirma
     *
     * @return Delibere
     */
    public function setNoteFirma($noteFirma)
    {
        $this->noteFirma = $noteFirma;

        return $this;
    }

    /**
     * Get noteFirma
     *
     * @return string
     */
    public function getNoteFirma()
    {
        return $this->noteFirma;
    }

    /**
     * Set idDirettore
     *
     * @param integer $idDirettore
     *
     * @return Delibere
     */
    public function setIdDirettore($idDirettore)
    {
        $this->idDirettore = $idDirettore;

        return $this;
    }

    /**
     * Get idDirettore
     *
     * @return integer
     */
    public function getIdDirettore()
    {
        return $this->idDirettore;
    }

    /**
     * Set dataDirettoreInvio
     *
     * @param \DateTime $dataDirettoreInvio
     *
     * @return Delibere
     */
    public function setDataDirettoreInvio($dataDirettoreInvio)
    {
        $this->dataDirettoreInvio = $dataDirettoreInvio;

        return $this;
    }

    /**
     * Get dataDirettoreInvio
     *
     * @return \DateTime
     */
    public function getDataDirettoreInvio()
    {
        return $this->dataDirettoreInvio;
    }

    /**
     * Set dataDirettoreRitorno
     *
     * @param \DateTime $dataDirettoreRitorno
     *
     * @return Delibere
     */
    public function setDataDirettoreRitorno($dataDirettoreRitorno)
    {
        $this->dataDirettoreRitorno = $dataDirettoreRitorno;

        return $this;
    }

    /**
     * Get dataDirettoreRitorno
     *
     * @return \DateTime
     */
    public function getDataDirettoreRitorno()
    {
        return $this->dataDirettoreRitorno;
    }

    /**
     * Set noteDirettore
     *
     * @param string $noteDirettore
     *
     * @return Delibere
     */
    public function setNoteDirettore($noteDirettore)
    {
        $this->noteDirettore = $noteDirettore;

        return $this;
    }

    /**
     * Get noteDirettore
     *
     * @return string
     */
    public function getNoteDirettore()
    {
        return $this->noteDirettore;
    }

    /**
     * Set invioMef
     *
     * @param integer $invioMef
     *
     * @return Delibere
     */
    public function setInvioMef($invioMef)
    {
        $this->invioMef = $invioMef;

        return $this;
    }

    /**
     * Get invioMef
     *
     * @return integer
     */
    public function getInvioMef()
    {
        return $this->invioMef;
    }

    /**
     * Set dataMefInvio
     *
     * @param \DateTime $dataMefInvio
     *
     * @return Delibere
     */
    public function setDataMefInvio($dataMefInvio)
    {
        $this->dataMefInvio = $dataMefInvio;

        return $this;
    }

    /**
     * Get dataMefInvio
     *
     * @return \DateTime
     */
    public function getDataMefInvio()
    {
        return $this->dataMefInvio;
    }

    /**
     * Set dataMefPec
     *
     * @param \DateTime $dataMefPec
     *
     * @return Delibere
     */
    public function setDataMefPec($dataMefPec)
    {
        $this->dataMefPec = $dataMefPec;

        return $this;
    }

    /**
     * Get dataMefPec
     *
     * @return \DateTime
     */
    public function getDataMefPec()
    {
        return $this->dataMefPec;
    }

    /**
     * Set dataMefRitorno
     *
     * @param \DateTime $dataMefRitorno
     *
     * @return Delibere
     */
    public function setDataMefRitorno($dataMefRitorno)
    {
        $this->dataMefRitorno = $dataMefRitorno;

        return $this;
    }

    /**
     * Get dataMefRitorno
     *
     * @return \DateTime
     */
    public function getDataMefRitorno()
    {
        return $this->dataMefRitorno;
    }

    /**
     * Set noteMef
     *
     * @param string $noteMef
     *
     * @return Delibere
     */
    public function setNoteMef($noteMef)
    {
        $this->noteMef = $noteMef;

        return $this;
    }

    /**
     * Get noteMef
     *
     * @return string
     */
    public function getNoteMef()
    {
        return $this->noteMef;
    }

    /**
     * Set idPresidente
     *
     * @param integer $idPresidente
     *
     * @return Delibere
     */
    public function setIdPresidente($idPresidente)
    {
        $this->idPresidente = $idPresidente;

        return $this;
    }

    /**
     * Get idPresidente
     *
     * @return integer
     */
    public function getIdPresidente()
    {
        return $this->idPresidente;
    }

    /**
     * Set dataPresidenteInvio
     *
     * @param \DateTime $dataPresidenteInvio
     *
     * @return Delibere
     */
    public function setDataPresidenteInvio($dataPresidenteInvio)
    {
        $this->dataPresidenteInvio = $dataPresidenteInvio;

        return $this;
    }

    /**
     * Get dataPresidenteInvio
     *
     * @return \DateTime
     */
    public function getDataPresidenteInvio()
    {
        return $this->dataPresidenteInvio;
    }

    /**
     * Set dataPresidenteRitorno
     *
     * @param \DateTime $dataPresidenteRitorno
     *
     * @return Delibere
     */
    public function setDataPresidenteRitorno($dataPresidenteRitorno)
    {
        $this->dataPresidenteRitorno = $dataPresidenteRitorno;

        return $this;
    }

    /**
     * Get dataPresidenteRitorno
     *
     * @return \DateTime
     */
    public function getDataPresidenteRitorno()
    {
        return $this->dataPresidenteRitorno;
    }

    /**
     * Set notePresidente
     *
     * @param string $notePresidente
     *
     * @return Delibere
     */
    public function setNotePresidente($notePresidente)
    {
        $this->notePresidente = $notePresidente;

        return $this;
    }

    /**
     * Get notePresidente
     *
     * @return string
     */
    public function getNotePresidente()
    {
        return $this->notePresidente;
    }

    /**
     * Set dataInvioCC
     *
     * @param \DateTime $dataInvioCC
     *
     * @return Delibere
     */
    public function setDataInvioCC($dataInvioCC)
    {
        $this->dataInvioCC = $dataInvioCC;

        return $this;
    }

    /**
     * Get dataInvioCC
     *
     * @return \DateTime
     */
    public function getDataInvioCC()
    {
        return $this->dataInvioCC;
    }

    /**
     * Set numeroCC
     *
     * @param integer $numeroCC
     *
     * @return Delibere
     */
    public function setNumeroCC($numeroCC)
    {
        $this->numeroCC = $numeroCC;

        return $this;
    }

    /**
     * Get numeroCC
     *
     * @return integer
     */
    public function getNumeroCC()
    {
        return $this->numeroCC;
    }

    /**
     * Set invioRagioneriaCC
     *
     * @param integer $invioRagioneriaCC
     *
     * @return Delibere
     */
    public function setInvioRagioneriaCC($invioRagioneriaCC)
    {
        $this->invioRagioneriaCC = $invioRagioneriaCC;

        return $this;
    }

    /**
     * Get invioRagioneriaCC
     *
     * @return integer
     */
    public function getInvioRagioneriaCC()
    {
        return $this->invioRagioneriaCC;
    }

    /**
     * Set dataRegistrazioneCC
     *
     * @param \DateTime $dataRegistrazioneCC
     *
     * @return Delibere
     */
    public function setDataRegistrazioneCC($dataRegistrazioneCC)
    {
        $this->dataRegistrazioneCC = $dataRegistrazioneCC;

        return $this;
    }

    /**
     * Get dataRegistrazioneCC
     *
     * @return \DateTime
     */
    public function getDataRegistrazioneCC()
    {
        return $this->dataRegistrazioneCC;
    }

    /**
     * Set idRegistroCC
     *
     * @param integer $idRegistroCC
     *
     * @return Delibere
     */
    public function setIdRegistroCC($idRegistroCC)
    {
        $this->idRegistroCC = $idRegistroCC;

        return $this;
    }

    /**
     * Get idRegistroCC
     *
     * @return integer
     */
    public function getIdRegistroCC()
    {
        return $this->idRegistroCC;
    }

    /**
     * Set foglioCC
     *
     * @param integer $foglioCC
     *
     * @return Delibere
     */
    public function setFoglioCC($foglioCC)
    {
        $this->foglioCC = $foglioCC;

        return $this;
    }

    /**
     * Get foglioCC
     *
     * @return integer
     */
    public function getFoglioCC()
    {
        return $this->foglioCC;
    }

    /**
     * Set noteCC
     *
     * @param string $noteCC
     *
     * @return Delibere
     */
    public function setNoteCC($noteCC)
    {
        $this->noteCC = $noteCC;

        return $this;
    }

    /**
     * Get noteCC
     *
     * @return string
     */
    public function getNoteCC()
    {
        return $this->noteCC;
    }

    /**
     * Set dataInvioCST
     *
     * @param \DateTime $dataInvioCST
     *
     * @return Delibere
     */
    public function setDataInvioCST($dataInvioCST)
    {
        $this->dataInvioCST = $dataInvioCST;

        return $this;
    }

    /**
     * Get dataInvioCST
     *
     * @return \DateTime
     */
    public function getDataInvioCST()
    {
        return $this->dataInvioCST;
    }

    /**
     * Set numeroInvioCST
     *
     * @param integer $numeroInvioCST
     *
     * @return Delibere
     */
    public function setNumeroInvioCST($numeroInvioCST)
    {
        $this->numeroInvioCST = $numeroInvioCST;

        return $this;
    }

    /**
     * Get numeroInvioCST
     *
     * @return integer
     */
    public function getNumeroInvioCST()
    {
        return $this->numeroInvioCST;
    }

    /**
     * Set noteInvioCST
     *
     * @param string $noteInvioCST
     *
     * @return Delibere
     */
    public function setNoteInvioCST($noteInvioCST)
    {
        $this->noteInvioCST = $noteInvioCST;

        return $this;
    }

    /**
     * Get noteInvioCST
     *
     * @return string
     */
    public function getNoteInvioCST()
    {
        return $this->noteInvioCST;
    }

    /**
     * Set dataRitornoCST
     *
     * @param \DateTime $dataRitornoCST
     *
     * @return Delibere
     */
    public function setDataRitornoCST($dataRitornoCST)
    {
        $this->dataRitornoCST = $dataRitornoCST;

        return $this;
    }

    /**
     * Get dataRitornoCST
     *
     * @return \DateTime
     */
    public function getDataRitornoCST()
    {
        return $this->dataRitornoCST;
    }

    /**
     * Set numeroRitornoCST
     *
     * @param integer $numeroRitornoCST
     *
     * @return Delibere
     */
    public function setNumeroRitornoCST($numeroRitornoCST)
    {
        $this->numeroRitornoCST = $numeroRitornoCST;

        return $this;
    }

    /**
     * Get numeroRitornoCST
     *
     * @return integer
     */
    public function getNumeroRitornoCST()
    {
        return $this->numeroRitornoCST;
    }

    /**
     * Set noteRitornoCST
     *
     * @param string $noteRitornoCST
     *
     * @return Delibere
     */
    public function setNoteRitornoCST($noteRitornoCST)
    {
        $this->noteRitornoCST = $noteRitornoCST;

        return $this;
    }

    /**
     * Get noteRitornoCST
     *
     * @return string
     */
    public function getNoteRitornoCST()
    {
        return $this->noteRitornoCST;
    }

    /**
     * Set dataInvioCU
     *
     * @param \DateTime $dataInvioCU
     *
     * @return Delibere
     */
    public function setDataInvioCU($dataInvioCU)
    {
        $this->dataInvioCU = $dataInvioCU;

        return $this;
    }

    /**
     * Get dataInvioCU
     *
     * @return \DateTime
     */
    public function getDataInvioCU()
    {
        return $this->dataInvioCU;
    }

    /**
     * Set numeroInvioCU
     *
     * @param integer $numeroInvioCU
     *
     * @return Delibere
     */
    public function setNumeroInvioCU($numeroInvioCU)
    {
        $this->numeroInvioCU = $numeroInvioCU;

        return $this;
    }

    /**
     * Get numeroInvioCU
     *
     * @return integer
     */
    public function getNumeroInvioCU()
    {
        return $this->numeroInvioCU;
    }

    /**
     * Set noteInvioCU
     *
     * @param string $noteInvioCU
     *
     * @return Delibere
     */
    public function setNoteInvioCU($noteInvioCU)
    {
        $this->noteInvioCU = $noteInvioCU;

        return $this;
    }

    /**
     * Get noteInvioCU
     *
     * @return string
     */
    public function getNoteInvioCU()
    {
        return $this->noteInvioCU;
    }

    /**
     * Set dataRitornoCU
     *
     * @param \DateTime $dataRitornoCU
     *
     * @return Delibere
     */
    public function setDataRitornoCU($dataRitornoCU)
    {
        $this->dataRitornoCU = $dataRitornoCU;

        return $this;
    }

    /**
     * Get dataRitornoCU
     *
     * @return \DateTime
     */
    public function getDataRitornoCU()
    {
        return $this->dataRitornoCU;
    }

    /**
     * Set numeroRitornoCU
     *
     * @param integer $numeroRitornoCU
     *
     * @return Delibere
     */
    public function setNumeroRitornoCU($numeroRitornoCU)
    {
        $this->numeroRitornoCU = $numeroRitornoCU;

        return $this;
    }

    /**
     * Get numeroRitornoCU
     *
     * @return integer
     */
    public function getNumeroRitornoCU()
    {
        return $this->numeroRitornoCU;
    }

    /**
     * Set noteRitornoCU
     *
     * @param string $noteRitornoCU
     *
     * @return Delibere
     */
    public function setNoteRitornoCU($noteRitornoCU)
    {
        $this->noteRitornoCU = $noteRitornoCU;

        return $this;
    }

    /**
     * Get noteRitornoCU
     *
     * @return string
     */
    public function getNoteRitornoCU()
    {
        return $this->noteRitornoCU;
    }

    /**
     * Set dataInvioCP
     *
     * @param \DateTime $dataInvioCP
     *
     * @return Delibere
     */
    public function setDataInvioCP($dataInvioCP)
    {
        $this->dataInvioCP = $dataInvioCP;

        return $this;
    }

    /**
     * Get dataInvioCP
     *
     * @return \DateTime
     */
    public function getDataInvioCP()
    {
        return $this->dataInvioCP;
    }

    /**
     * Set numeroInvioCP
     *
     * @param integer $numeroInvioCP
     *
     * @return Delibere
     */
    public function setNumeroInvioCP($numeroInvioCP)
    {
        $this->numeroInvioCP = $numeroInvioCP;

        return $this;
    }

    /**
     * Get numeroInvioCP
     *
     * @return integer
     */
    public function getNumeroInvioCP()
    {
        return $this->numeroInvioCP;
    }

    /**
     * Set noteInvioCP
     *
     * @param string $noteInvioCP
     *
     * @return Delibere
     */
    public function setNoteInvioCP($noteInvioCP)
    {
        $this->noteInvioCP = $noteInvioCP;

        return $this;
    }

    /**
     * Get noteInvioCP
     *
     * @return string
     */
    public function getNoteInvioCP()
    {
        return $this->noteInvioCP;
    }

    /**
     * Set dataRitornoCP
     *
     * @param \DateTime $dataRitornoCP
     *
     * @return Delibere
     */
    public function setDataRitornoCP($dataRitornoCP)
    {
        $this->dataRitornoCP = $dataRitornoCP;

        return $this;
    }

    /**
     * Get dataRitornoCP
     *
     * @return \DateTime
     */
    public function getDataRitornoCP()
    {
        return $this->dataRitornoCP;
    }

    /**
     * Set numeroRitornoCP
     *
     * @param integer $numeroRitornoCP
     *
     * @return Delibere
     */
    public function setNumeroRitornoCP($numeroRitornoCP)
    {
        $this->numeroRitornoCP = $numeroRitornoCP;

        return $this;
    }

    /**
     * Get numeroRitornoCP
     *
     * @return integer
     */
    public function getNumeroRitornoCP()
    {
        return $this->numeroRitornoCP;
    }

    /**
     * Set noteRitornoCP
     *
     * @param string $noteRitornoCP
     *
     * @return Delibere
     */
    public function setNoteRitornoCP($noteRitornoCP)
    {
        $this->noteRitornoCP = $noteRitornoCP;

        return $this;
    }

    /**
     * Get noteRitornoCP
     *
     * @return string
     */
    public function getNoteRitornoCP()
    {
        return $this->noteRitornoCP;
    }

    /**
     * Set dataInvioP
     *
     * @param \DateTime $dataInvioP
     *
     * @return Delibere
     */
    public function setDataInvioP($dataInvioP)
    {
        $this->dataInvioP = $dataInvioP;

        return $this;
    }

    /**
     * Get dataInvioP
     *
     * @return \DateTime
     */
    public function getDataInvioP()
    {
        return $this->dataInvioP;
    }

    /**
     * Set noteP
     *
     * @param string $noteP
     *
     * @return Delibere
     */
    public function setNoteP($noteP)
    {
        $this->noteP = $noteP;

        return $this;
    }

    /**
     * Get noteP
     *
     * @return string
     */
    public function getNoteP()
    {
        return $this->noteP;
    }

    /**
     * Set dataInvioGU
     *
     * @param \DateTime $dataInvioGU
     *
     * @return Delibere
     */
    public function setDataInvioGU($dataInvioGU)
    {
        $this->dataInvioGU = $dataInvioGU;

        return $this;
    }

    /**
     * Get dataInvioGU
     *
     * @return \DateTime
     */
    public function getDataInvioGU()
    {
        return $this->dataInvioGU;
    }

    /**
     * Set numeroInvioGU
     *
     * @param integer $numeroInvioGU
     *
     * @return Delibere
     */
    public function setNumeroInvioGU($numeroInvioGU)
    {
        $this->numeroInvioGU = $numeroInvioGU;

        return $this;
    }

    /**
     * Get numeroInvioGU
     *
     * @return integer
     */
    public function getNumeroInvioGU()
    {
        return $this->numeroInvioGU;
    }

    /**
     * Set tipoGU
     *
     * @param integer $tipoGU
     *
     * @return Delibere
     */
    public function setTipoGU($tipoGU)
    {
        $this->tipoGU = $tipoGU;

        return $this;
    }

    /**
     * Get tipoGU
     *
     * @return integer
     */
    public function getTipoGU()
    {
        return $this->tipoGU;
    }

    /**
     * Set dataGU
     *
     * @param \DateTime $dataGU
     *
     * @return Delibere
     */
    public function setDataGU($dataGU)
    {
        $this->dataGU = $dataGU;

        return $this;
    }

    /**
     * Get dataGU
     *
     * @return \DateTime
     */
    public function getDataGU()
    {
        return $this->dataGU;
    }

    /**
     * Set numeroGU
     *
     * @param integer $numeroGU
     *
     * @return Delibere
     */
    public function setNumeroGU($numeroGU)
    {
        $this->numeroGU = $numeroGU;

        return $this;
    }

    /**
     * Get numeroGU
     *
     * @return integer
     */
    public function getNumeroGU()
    {
        return $this->numeroGU;
    }

    /**
     * Set dataEcGU
     *
     * @param \DateTime $dataEcGU
     *
     * @return Delibere
     */
    public function setDataEcGU($dataEcGU)
    {
        $this->dataEcGU = $dataEcGU;

        return $this;
    }

    /**
     * Get dataEcGU
     *
     * @return \DateTime
     */
    public function getDataEcGU()
    {
        return $this->dataEcGU;
    }

    /**
     * Set numeroEcGU
     *
     * @param integer $numeroEcGU
     *
     * @return Delibere
     */
    public function setNumeroEcGU($numeroEcGU)
    {
        $this->numeroEcGU = $numeroEcGU;

        return $this;
    }

    /**
     * Get numeroEcGU
     *
     * @return integer
     */
    public function getNumeroEcGU()
    {
        return $this->numeroEcGU;
    }

    /**
     * Set dataCoGU
     *
     * @param \DateTime $dataCoGU
     *
     * @return Delibere
     */
    public function setDataCoGU($dataCoGU)
    {
        $this->dataCoGU = $dataCoGU;

        return $this;
    }

    /**
     * Get dataCoGU
     *
     * @return \DateTime
     */
    public function getDataCoGU()
    {
        return $this->dataCoGU;
    }

    /**
     * Set numeroCoGU
     *
     * @param integer $numeroCoGU
     *
     * @return Delibere
     */
    public function setNumeroCoGU($numeroCoGU)
    {
        $this->numeroCoGU = $numeroCoGU;

        return $this;
    }

    /**
     * Get numeroCoGU
     *
     * @return integer
     */
    public function getNumeroCoGU()
    {
        return $this->numeroCoGU;
    }

    /**
     * Set pubblicazioneGU
     *
     * @param integer $pubblicazioneGU
     *
     * @return Delibere
     */
    public function setPubblicazioneGU($pubblicazioneGU)
    {
        $this->pubblicazioneGU = $pubblicazioneGU;

        return $this;
    }

    /**
     * Get pubblicazioneGU
     *
     * @return integer
     */
    public function getPubblicazioneGU()
    {
        return $this->pubblicazioneGU;
    }

    /**
     * Set noteGU
     *
     * @param string $noteGU
     *
     * @return Delibere
     */
    public function setNoteGU($noteGU)
    {
        $this->noteGU = $noteGU;

        return $this;
    }

    /**
     * Get noteGU
     *
     * @return string
     */
    public function getNoteGU()
    {
        return $this->noteGU;
    }

    /**
     * Set invioSegretario
     *
     * @param integer $invioSegretario
     *
     * @return Delibere
     */
    public function setInvioSegretario($invioSegretario)
    {
        $this->invioSegretario = $invioSegretario;

        return $this;
    }

    /**
     * Get invioSegretario
     *
     * @return integer
     */
    public function getInvioSegretario()
    {
        return $this->invioSegretario;
    }

    /**
     * Set dataSegretarioInvio
     *
     * @param \DateTime $dataSegretarioInvio
     *
     * @return Delibere
     */
    public function setDataSegretarioInvio($dataSegretarioInvio)
    {
        $this->dataSegretarioInvio = $dataSegretarioInvio;

        return $this;
    }

    /**
     * Get dataSegretarioInvio
     *
     * @return \DateTime
     */
    public function getDataSegretarioInvio()
    {
        return $this->dataSegretarioInvio;
    }

    /**
     * Set dataSegretarioRitorno
     *
     * @param \DateTime $dataSegretarioRitorno
     *
     * @return Delibere
     */
    public function setDataSegretarioRitorno($dataSegretarioRitorno)
    {
        $this->dataSegretarioRitorno = $dataSegretarioRitorno;

        return $this;
    }

    /**
     * Get dataSegretarioRitorno
     *
     * @return \DateTime
     */
    public function getDataSegretarioRitorno()
    {
        return $this->dataSegretarioRitorno;
    }

    /**
     * Set noteSegretario
     *
     * @param string $noteSegretario
     *
     * @return Delibere
     */
    public function setNoteSegretario($noteSegretario)
    {
        $this->noteSegretario = $noteSegretario;

        return $this;
    }

    /**
     * Get noteSegretario
     *
     * @return string
     */
    public function getNoteSegretario()
    {
        return $this->noteSegretario;
    }

    /**
     * Set idSegretario
     *
     * @param integer $idSegretario
     *
     * @return Delibere
     */
    public function setIdSegretario($idSegretario)
    {
        $this->idSegretario = $idSegretario;

        return $this;
    }

    /**
     * Get idSegretario
     *
     * @return integer
     */
    public function getIdSegretario()
    {
        return $this->idSegretario;
    }

    /**
     * Set tipoRegistrazioneCC
     *
     * @param integer $tipoRegistrazioneCC
     *
     * @return Delibere
     */
    public function setTipoRegistrazioneCC($tipoRegistrazioneCC)
    {
        $this->tipoRegistrazioneCC = $tipoRegistrazioneCC;

        return $this;
    }

    /**
     * Get tipoRegistrazioneCC
     *
     * @return integer
     */
    public function getTipoRegistrazioneCC()
    {
        return $this->tipoRegistrazioneCC;
    }

    /**
     * Set situazione
     *
     * @param integer $situazione
     *
     * @return Delibere
     */
    public function setSituazione($situazione)
    {
        $this->situazione = $situazione;

        return $this;
    }

    /**
     * Get situazione
     *
     * @return integer
     */
    public function getSituazione()
    {
        return $this->situazione;
    }
}
