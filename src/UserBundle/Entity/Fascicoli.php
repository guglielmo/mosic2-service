<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Fascicoli
 *
 * @ORM\Table(name="msc_fascicoli")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\FascicoliRepository")
 */
class Fascicoli
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="RelAmministrazioniFascicoli", mappedBy="idFascicoli")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="codice_repertorio", type="integer")
     */
    private $codiceRepertorio;
		
    /**
     * @var int
     *
     * @ORM\Column(name="codice_titolario", type="integer")
     */
    private $codiceTitolario;

    /**
     * @var int
     *
     * @ORM\Column(name="id_titolari", type="integer")
     */
    private $idTitolari;


    /**
     * @var int
     *
     * @ORM\Column(name="numero_fascicolo", type="integer")
     */
    private $numeroFascicolo;

    /**
     * @var string
     *
     * @ORM\Column(name="argomento", type="string", length=255)
     */
    private $argomento;
		
    /**
     * @var int
     *
     * @ORM\Column(name="id_amministrazione", type="integer")
     */
    private $idAmministrazione;


    /**
     * @var \Date
     *
     * @ORM\Column(name="data_magazzino", type="date")
     */
    private $dataMagazzino;
		
    /**
     * @var \Date
     *
     * @ORM\Column(name="data_cipe", type="date")
     */
    private $dataCipe;

    /**
     * @var \Date
     *
     * @ORM\Column(name="data_cipe2", type="date")
     */
    private $dataCipe2;
		
		/**
     * @var \int
     *
     * @ORM\Column(name="archiviazione_repertorio", type="integer")
     */
    private $archiviazioneRepertorio;
		
    /**
     * @var \string
     *
     * @ORM\Column(name="annotazioni", type="string", length=255)
     */
    private $annotazioni;

		/**
     * @var \int
     *
     * @ORM\Column(name="id_numeri_delibera", type="integer")
     */
    private $idNumeriDelibera;

		/**
     * @var \int
     *
     * @ORM\Column(name="id_esiti_cipe", type="integer")
     */
    private $idEsitiCipe;

		/**
     * @var \int
     *
     * @ORM\Column(name="id_archivio_repertorio", type="integer")
     */
    private $idArchivioRepertorio;

		
    
    public function __construct()
    {

        $this->codiceRepertorio = 0;
        $this->codiceTitolario = 0;
        $this->idTitolari = 0;
        //$this->idAmministrazione = 0;
        $this->dataCipe = new \DateTime("0000-00-00");
        $this->dataCipe2 = new \DateTime("0000-00-00");
        $this->idNumeriDelibera = 0;
        $this->idEsitiCipe = 0;
        $this->idArchivioRepertorio = 0;
        $this->idAmministrazione = new ArrayCollection();
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
     * Set codiceRepertorio
     *
     * @param integer $codiceRepertorio
     *
     * @return Fascicoli
     */
    public function setCodiceRepertorio($codiceRepertorio)
    {
        $this->codiceRepertorio = $codiceRepertorio;

        return $this;
    }

    /**
     * Get codiceRepertorio
     *
     * @return integer
     */
    public function getCodiceRepertorio()
    {
        return $this->codiceRepertorio;
    }

    /**
     * Set codiceTitolario
     *
     * @param integer $codiceTitolario
     *
     * @return Fascicoli
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
     * Set idTitolari
     *
     * @param integer $idTitolari
     *
     * @return Fascicoli
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
     * Set numeroFascicolo
     *
     * @param integer $numeroFascicolo
     *
     * @return Fascicoli
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
     * Set argomento
     *
     * @param string $argomento
     *
     * @return Fascicoli
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
     * Set idAmministrazione
     *
     * @param integer $idAmministrazione
     *
     * @return Fascicoli
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
     * Set dataMagazzino
     *
     * @param \DateTime $dataMagazzino
     *
     * @return Fascicoli
     */
    public function setDataMagazzino($dataMagazzino)
    {
        $this->dataMagazzino = $dataMagazzino;

        return $this;
    }

    /**
     * Get dataMagazzino
     *
     * @return \DateTime
     */
    public function getDataMagazzino()
    {
        return $this->dataMagazzino;
    }

    /**
     * Set dataCipe
     *
     * @param \DateTime $dataCipe
     *
     * @return Fascicoli
     */
    public function setDataCipe($dataCipe)
    {
        $this->dataCipe = $dataCipe;

        return $this;
    }

    /**
     * Get dataCipe
     *
     * @return \DateTime
     */
    public function getDataCipe()
    {
        return $this->dataCipe;
    }

    /**
     * Set dataCipe2
     *
     * @param \DateTime $dataCipe2
     *
     * @return Fascicoli
     */
    public function setDataCipe2($dataCipe2)
    {
        $this->dataCipe2 = $dataCipe2;

        return $this;
    }

    /**
     * Get dataCipe2
     *
     * @return \DateTime
     */
    public function getDataCipe2()
    {
        return $this->dataCipe2;
    }

    /**
     * Set archiviazioneRepertorio
     *
     * @param integer $archiviazioneRepertorio
     *
     * @return Fascicoli
     */
    public function setArchiviazioneRepertorio($archiviazioneRepertorio)
    {
        $this->archiviazioneRepertorio = $archiviazioneRepertorio;

        return $this;
    }

    /**
     * Get archiviazioneRepertorio
     *
     * @return integer
     */
    public function getArchiviazioneRepertorio()
    {
        return $this->archiviazioneRepertorio;
    }

    /**
     * Set annotazioni
     *
     * @param string $annotazioni
     *
     * @return Fascicoli
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
     * Set idNumeriDelibera
     *
     * @param integer $idNumeriDelibera
     *
     * @return Fascicoli
     */
    public function setIdNumeriDelibera($idNumeriDelibera)
    {
        $this->idNumeriDelibera = $idNumeriDelibera;

        return $this;
    }

    /**
     * Get idNumeriDelibera
     *
     * @return integer
     */
    public function getIdNumeriDelibera()
    {
        return $this->idNumeriDelibera;
    }

    /**
     * Set idEsitiCipe
     *
     * @param integer $idEsitiCipe
     *
     * @return Fascicoli
     */
    public function setIdEsitiCipe($idEsitiCipe)
    {
        $this->idEsitiCipe = $idEsitiCipe;

        return $this;
    }

    /**
     * Get idEsitiCipe
     *
     * @return integer
     */
    public function getIdEsitiCipe()
    {
        return $this->idEsitiCipe;
    }

    /**
     * Set idArchivioRepertorio
     *
     * @param integer $idArchivioRepertorio
     *
     * @return Fascicoli
     */
    public function setIdArchivioRepertorio($idArchivioRepertorio)
    {
        $this->idArchivioRepertorio = $idArchivioRepertorio;

        return $this;
    }

    /**
     * Get idArchivioRepertorio
     *
     * @return integer
     */
    public function getIdArchivioRepertorio()
    {
        return $this->idArchivioRepertorio;
    }
}
