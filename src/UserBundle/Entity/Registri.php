<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

/**
 * Registri
 *
 * @ORM\Table("msc_registri")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RegistriRepository")
 */
class Registri {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
		
		/**
		* @ORM\Column(name="data_arrivo", type="date")
		*/
    private $dataArrivo;
		
		/**
		* @ORM\Column(name="protocollo_arrivo", type="string", length=255)
		*/
    private $protocolloArrivo;

		/**
		* @ORM\Column(name="data_mittente", type="date")
		*/
    private $dataMittente;
		
		/**
		* @ORM\Column(name="protocollo_mittente", type="string", length=255)
		*/
    private $protocolloMittente;

		/**
		* @ORM\Column(name="oggetto", type="text")
		*/
    private $oggetto;
		
		/**
		* @ORM\Column(name="id_amministrazione", type="integer")
		*/
    private $idAmministrazione;

		/**
    * @ORM\Column(name="mittente", type="string", length=512)
		*/
    private $mittente;
		
		/**
		* @ORM\Column(name="codice_titolario", type="integer")
		*/
    private $codiceTitolario;


		/**
		* @ORM\Column(name="numero_fascicolo", type="integer")
		*/
    private $numeroFascicolo;

		/**
		* @ORM\Column(name="numero_sottofascicolo", type="integer")
		*/
    private $numeroSottofascicolo;

		/**
		* @ORM\Column(name="proposta_cipe", type="boolean")
		*/
    private $propostaCipe;
		
    /**
     * @ORM\Column(name="annotazioni", type="string", length=255)
     */
    private $annotazioni;
		
		/**
		* @ORM\Column(name="id_sottofascicoli", type="integer")
		*/
    private $idSottofascicoli;
		
		/**
		* @ORM\Column(name="id_mittenti", type="integer")
		*/
    private $idMittenti;
		
		/**
		* @ORM\Column(name="id_titolari", type="integer")
		*/
    private $idTitolari;

		/**
		* @ORM\Column(name="id_fascicoli", type="integer")
		*/
    private $idFascicoli;

		
		
    public function __construct()
    {
        $this->codiceTitolario = 0;
        $this->idFascicoli = 0;
        $this->idAmministrazione = 0;
        $this->idMittenti = 0;
        $this->idSottofascicoli = 0;
        $this->mittente = '';
        $this->numeroSottofascicolo = 0;
        $this->propostaCipe = 0;
	    $this->dataMittente = new \DateTime("0000-00-00");
        $this->dataArrivo = new \DateTime("0000-00-00");
		$this->protocolloArrivo = '';
        $this->protocolloMittente = '';
        $this->oggetto = '';
        $this->numeroFascicolo = 0;
        $this->idTitolari = 0;
        $this->annotazioni = '';
        $this->numeroFascicolo = 0;

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
     * Set dataArrivo
     *
     * @param \DateTime $dataArrivo
     *
     * @return Registri
     */
    public function setDataArrivo($dataArrivo)
    {
        $this->dataArrivo = $dataArrivo;

        return $this;
    }

    /**
     * Get dataArrivo
     *
     * @return \DateTime
     */
    public function getDataArrivo()
    {
        return $this->dataArrivo;
    }

    /**
     * Set protocolloArrivo
     *
     * @param string $protocolloArrivo
     *
     * @return Registri
     */
    public function setProtocolloArrivo($protocolloArrivo)
    {
        $this->protocolloArrivo = $protocolloArrivo;

        return $this;
    }

    /**
     * Get protocolloArrivo
     *
     * @return string
     */
    public function getProtocolloArrivo()
    {
        return $this->protocolloArrivo;
    }

    /**
     * Set dataMittente
     *
     * @param \DateTime $dataMittente
     *
     * @return Registri
     */
    public function setDataMittente($dataMittente)
    {
        $this->dataMittente = $dataMittente;

        return $this;
    }

    /**
     * Get dataMittente
     *
     * @return \DateTime
     */
    public function getDataMittente()
    {
        return $this->dataMittente;
    }

    /**
     * Set protocolloMittente
     *
     * @param string $protocolloMittente
     *
     * @return Registri
     */
    public function setProtocolloMittente($protocolloMittente)
    {
        $this->protocolloMittente = $protocolloMittente;

        return $this;
    }

    /**
     * Get protocolloMittente
     *
     * @return string
     */
    public function getProtocolloMittente()
    {
        return $this->protocolloMittente;
    }

    /**
     * Set oggetto
     *
     * @param string $oggetto
     *
     * @return Registri
     */
    public function setOggetto($oggetto)
    {
        $this->oggetto = $oggetto;

        return $this;
    }

    /**
     * Get oggetto
     *
     * @return string
     */
    public function getOggetto()
    {
        return $this->oggetto;
    }

    /**
     * Set idAmministrazione
     *
     * @param integer $idAmministrazione
     *
     * @return Registri
     */
    public function setIdAmministrazione($idAmministrazione)
    {
        $this->idAmministrazione = $idAmministrazione;

        return $this;
    }

    /**
     * Get idAmministrazione
     *
     * @return integer
     */
    public function getIdAmministrazione()
    {
        return $this->idAmministrazione;
    }

    /**
     * Set mittente
     *
     * @param string $mittente
     *
     * @return Registri
     */
    public function setMittente($mittente)
    {
        $this->mittente = $mittente;

        return $this;
    }

    /**
     * Get mittente
     *
     * @return string
     */
    public function getMittente()
    {
        return $this->mittente;
    }

    /**
     * Set codiceTitolario
     *
     * @param integer $codiceTitolario
     *
     * @return Registri
     */
    public function setCodiceTitolario($codiceTitolario)
    {
        $this->codiceTitolario = $codiceTitolario;

        return $this;
    }

    /**
     * Get codiceTitolario
     *
     * @return integer
     */
    public function getCodiceTitolario()
    {
        return $this->codiceTitolario;
    }

    /**
     * Set numeroFascicolo
     *
     * @param integer $numeroFascicolo
     *
     * @return Registri
     */
    public function setNumeroFascicolo($numeroFascicolo)
    {
        $this->numeroFascicolo = $numeroFascicolo;

        return $this;
    }

    /**
     * Get numeroFascicolo
     *
     * @return integer
     */
    public function getNumeroFascicolo()
    {
        return $this->numeroFascicolo;
    }

    /**
     * Set numeroSottofascicolo
     *
     * @param integer $numeroSottofascicolo
     *
     * @return Registri
     */
    public function setNumeroSottofascicolo($numeroSottofascicolo)
    {
        $this->numeroSottofascicolo = $numeroSottofascicolo;

        return $this;
    }

    /**
     * Get numeroSottofascicolo
     *
     * @return integer
     */
    public function getNumeroSottofascicolo()
    {
        return $this->numeroSottofascicolo;
    }

    /**
     * Set propostaCipe
     *
     * @param boolean $propostaCipe
     *
     * @return Registri
     */
    public function setPropostaCipe($propostaCipe)
    {
        $this->propostaCipe = $propostaCipe;

        return $this;
    }

    /**
     * Get propostaCipe
     *
     * @return boolean
     */
    public function getPropostaCipe()
    {
        return $this->propostaCipe;
    }

    /**
     * Set annotazioni
     *
     * @param string $annotazioni
     *
     * @return Registri
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
     * Set idSottofascicoli
     *
     * @param integer $idSottofascicoli
     *
     * @return Registri
     */
    public function setIdSottofascicoli($idSottofascicoli)
    {
        $this->idSottofascicoli = $idSottofascicoli;

        return $this;
    }

    /**
     * Get idSottofascicoli
     *
     * @return integer
     */
    public function getIdSottofascicoli()
    {
        return $this->idSottofascicoli;
    }

    /**
     * Set idMittenti
     *
     * @param integer $idMittenti
     *
     * @return Registri
     */
    public function setIdMittenti($idMittenti)
    {
        $this->idMittenti = $idMittenti;

        return $this;
    }

    /**
     * Get idMittenti
     *
     * @return integer
     */
    public function getIdMittenti()
    {
        return $this->idMittenti;
    }

    /**
     * Set idTitolari
     *
     * @param integer $idTitolari
     *
     * @return Registri
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
     * @return Registri
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
}
