<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DelibereCC
 *
 * @ORM\Table(name="msc_delibere_cc")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\DelibereCCRepository")
 */
class DelibereCC
{
    /** ########################################################## DELIBERE CORTE DEI CONTI######################
     *  #########################################################################################################
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="id_delibere", type="integer")
     */
    private $idDelibere;

    /**
     * @var integer
     * @ORM\Column(name="tipo_documento", type="integer")
     */
    private $tipoDocumento;

    /**
     * @var \Date
     * @ORM\Column(name="data_rilievo", type="date")
     */
    private $dataRilievo;

    /**
     * @var string
     * @ORM\Column(name="numero_rilievo", type="string", length=255)
     */
    private $numeroRilievo;

    /**
     * @var \Date
     * @ORM\Column(name="data_risposta", type="date")
     */
    private $dataRisposta;

    /**
     * @var string
     * @ORM\Column(name="numero_risposta", type="string", length=255)
     */
    private $numeroRisposta;

    /**
     * @var integer
     * @ORM\Column(name="giorni_rilievo", type="integer")
     */
    private $giorniRilievo;

    /**
     * @var integer
     * @ORM\Column(name="tipo_rilievo", type="integer")
     */
    private $tipoRilievo;

    /**
     * @var string
     * @ORM\Column(name="note_rilievo", type="string", length=255)
     */
    private $note_rilievo;



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
     * Set idDelibere
     *
     * @param integer $idDelibere
     *
     * @return DelibereCC
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
     * Set tipoDocumento
     *
     * @param integer $tipoDocumento
     *
     * @return DelibereCC
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return integer
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set dataRilievo
     *
     * @param \DateTime $dataRilievo
     *
     * @return DelibereCC
     */
    public function setDataRilievo($dataRilievo)
    {
        $this->dataRilievo = $dataRilievo;

        return $this;
    }

    /**
     * Get dataRilievo
     *
     * @return \DateTime
     */
    public function getDataRilievo()
    {
        return $this->dataRilievo;
    }

    /**
     * Set numeroRilievo
     *
     * @param string $numeroRilievo
     *
     * @return DelibereCC
     */
    public function setNumeroRilievo($numeroRilievo)
    {
        $this->numeroRilievo = $numeroRilievo;

        return $this;
    }

    /**
     * Get numeroRilievo
     *
     * @return string
     */
    public function getNumeroRilievo()
    {
        return $this->numeroRilievo;
    }

    /**
     * Set dataRisposta
     *
     * @param \DateTime $dataRisposta
     *
     * @return DelibereCC
     */
    public function setDataRisposta($dataRisposta)
    {
        $this->dataRisposta = $dataRisposta;

        return $this;
    }

    /**
     * Get dataRisposta
     *
     * @return \DateTime
     */
    public function getDataRisposta()
    {
        return $this->dataRisposta;
    }

    /**
     * Set numeroRisposta
     *
     * @param string $numeroRisposta
     *
     * @return DelibereCC
     */
    public function setNumeroRisposta($numeroRisposta)
    {
        $this->numeroRisposta = $numeroRisposta;

        return $this;
    }

    /**
     * Get numeroRisposta
     *
     * @return string
     */
    public function getNumeroRisposta()
    {
        return $this->numeroRisposta;
    }

    /**
     * Set giorniRilievo
     *
     * @param integer $giorniRilievo
     *
     * @return DelibereCC
     */
    public function setGiorniRilievo($giorniRilievo)
    {
        $this->giorniRilievo = $giorniRilievo;

        return $this;
    }

    /**
     * Get giorniRilievo
     *
     * @return integer
     */
    public function getGiorniRilievo()
    {
        return $this->giorniRilievo;
    }

    /**
     * Set tipoRilievo
     *
     * @param integer $tipoRilievo
     *
     * @return DelibereCC
     */
    public function setTipoRilievo($tipoRilievo)
    {
        $this->tipoRilievo = $tipoRilievo;

        return $this;
    }

    /**
     * Get tipoRilievo
     *
     * @return integer
     */
    public function getTipoRilievo()
    {
        return $this->tipoRilievo;
    }

    /**
     * Set noteRilievo
     *
     * @param string $noteRilievo
     *
     * @return DelibereCC
     */
    public function setNoteRilievo($noteRilievo)
    {
        $this->note_rilievo = $noteRilievo;

        return $this;
    }

    /**
     * Get noteRilievo
     *
     * @return string
     */
    public function getNoteRilievo()
    {
        return $this->note_rilievo;
    }
}
