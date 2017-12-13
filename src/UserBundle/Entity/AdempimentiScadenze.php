<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdempimentiScadenze
 *
 * @ORM\Table(name="msc_adempimenti_scadenze")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\AdempimentiScadenzeRepository")
 */
class AdempimentiScadenze
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
     * @ORM\Column(name="id_adempimenti", type="integer", nullable=true)
     */
    private $idAdempimenti;

    /**
     * @var \Date
     * @ORM\Column(name="data", type="date", nullable=true)
     */
    private $data;

    /**
     * @var int
     * @ORM\Column(name="stato", type="integer", nullable=true)
     */
    private $stato;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;


    public function __construct() {
        $this->data = new \DateTime();
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
     * Set data
     *
     * @param \DateTime $data
     *
     * @return AdempimentiScadenze
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
     * Set stato
     *
     * @param integer $stato
     *
     * @return AdempimentiScadenze
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
     * Set note
     *
     * @param string $note
     *
     * @return AdempimentiScadenze
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
     * Set idAdempimenti
     *
     * @param integer $idAdempimenti
     *
     * @return AdempimentiScadenze
     */
    public function setIdAdempimenti($idAdempimenti)
    {
        $this->idAdempimenti = $idAdempimenti;

        return $this;
    }

    /**
     * Get idAdempimenti
     *
     * @return integer
     */
    public function getIdAdempimenti()
    {
        return $this->idAdempimenti;
    }
}
