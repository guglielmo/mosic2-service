<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DelibereGiorni
 *
 * @ORM\Table(name="msc_delibere_giorni")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\DelibereGiorniRepository")
 */
class DelibereGiorni
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
     * @var int
     * @ORM\Column(name="id_delibere", type="integer", nullable=true)
     */
    private $idDelibere;

    /**
     * @var \Date
     * @ORM\Column(name="acquisizione_segretario", type="date", nullable=true)
     */
    private $acquisizioneSegretario;

    /**
     * @var int
     * @ORM\Column(name="giorni_capo_dipartimento", type="integer", nullable=true)
     */
    private $giorniCapoDipartimento;

    /**
     * @var int
     * @ORM\Column(name="giorni_mef", type="integer", nullable=true)
     */
    private $giorniMef;

    /**
     * @var int
     * @ORM\Column(name="giorniSegretario", type="integer", nullable=true)
     */
    private $giorniSegretario;

    /**
     * @var int
     * @ORM\Column(name="giorniPresidente", type="integer", nullable=true)
     */
    private $giorniPresidente;

    /**
     * @var int
     * @ORM\Column(name="giorni_cc", type="integer", nullable=true)
     */
    private $giorniCC;

    /**
     * @var int
     * @ORM\Column(name="giorni_gu", type="integer", nullable=true)
     */
    private $giorniGU;



    public function __construct() {
        $this->acquisizioneSegretario = new \DateTime("0000-00-00");
    }



    //###################################### GETTER / SETTER ######################################




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
     * Set acquisizioneSegretario
     *
     * @param \DateTime $acquisizioneSegretario
     *
     * @return DelibereGiorni
     */
    public function setAcquisizioneSegretario($acquisizioneSegretario)
    {
        $this->acquisizioneSegretario = $acquisizioneSegretario;

        return $this;
    }

    /**
     * Get acquisizioneSegretario
     *
     * @return \DateTime
     */
    public function getAcquisizioneSegretario()
    {
        return $this->acquisizioneSegretario;
    }

    /**
     * Set giorniCapoDipartimento
     *
     * @param integer $giorniCapoDipartimento
     *
     * @return DelibereGiorni
     */
    public function setGiorniCapoDipartimento($giorniCapoDipartimento)
    {
        $this->giorniCapoDipartimento = $giorniCapoDipartimento;

        return $this;
    }

    /**
     * Get giorniCapoDipartimento
     *
     * @return integer
     */
    public function getGiorniCapoDipartimento()
    {
        return $this->giorniCapoDipartimento;
    }

    /**
     * Set giorniMef
     *
     * @param integer $giorniMef
     *
     * @return DelibereGiorni
     */
    public function setGiorniMef($giorniMef)
    {
        $this->giorniMef = $giorniMef;

        return $this;
    }

    /**
     * Get giorniMef
     *
     * @return integer
     */
    public function getGiorniMef()
    {
        return $this->giorniMef;
    }

    /**
     * Set giorniSegretario
     *
     * @param integer $giorniSegretario
     *
     * @return DelibereGiorni
     */
    public function setGiorniSegretario($giorniSegretario)
    {
        $this->giorniSegretario = $giorniSegretario;

        return $this;
    }

    /**
     * Get giorniSegretario
     *
     * @return integer
     */
    public function getGiorniSegretario()
    {
        return $this->giorniSegretario;
    }

    /**
     * Set giorniPresidente
     *
     * @param integer $giorniPresidente
     *
     * @return DelibereGiorni
     */
    public function setGiorniPresidente($giorniPresidente)
    {
        $this->giorniPresidente = $giorniPresidente;

        return $this;
    }

    /**
     * Get giorniPresidente
     *
     * @return integer
     */
    public function getGiorniPresidente()
    {
        return $this->giorniPresidente;
    }

    /**
     * Set giorniCC
     *
     * @param integer $giorniCC
     *
     * @return DelibereGiorni
     */
    public function setGiorniCC($giorniCC)
    {
        $this->giorniCC = $giorniCC;

        return $this;
    }

    /**
     * Get giorniCC
     *
     * @return integer
     */
    public function getGiorniCC()
    {
        return $this->giorniCC;
    }

    /**
     * Set giorniGU
     *
     * @param integer $giorniGU
     *
     * @return DelibereGiorni
     */
    public function setGiorniGU($giorniGU)
    {
        $this->giorniGU = $giorniGU;

        return $this;
    }

    /**
     * Get giorniGU
     *
     * @return integer
     */
    public function getGiorniGU()
    {
        return $this->giorniGU;
    }

    /**
     * Set idDelibere
     *
     * @param integer $idDelibere
     *
     * @return DelibereGiorni
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
}
