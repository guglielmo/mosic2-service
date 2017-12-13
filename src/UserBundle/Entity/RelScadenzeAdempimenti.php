<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelScadenzeAdempimenti
 *
 * @ORM\Table(name="msc_rel_scadenze_adempimenti",indexes={@Index(name="id_adempimenti_idx", columns={"id_adempimenti"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelScadenzeAdempimentiRepository")
 */
class RelScadenzeAdempimenti
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
     *
     * @ORM\Column(name="id_adempimenti", type="integer")
     */
    private $idAdempimenti;

    /**
     * @var int
     *
     * @ORM\Column(name="id_scadenze", type="integer")
     * */
    private $idScadenze;






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
     * Set idAdempimenti
     *
     * @param integer $idAdempimenti
     *
     * @return RelScadenzeAdempimenti
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

    /**
     * Set idScadenze
     *
     * @param integer $idScadenze
     *
     * @return RelScadenzeAdempimenti
     */
    public function setIdScadenze($idScadenze)
    {
        $this->idScadenze = $idScadenze;

        return $this;
    }

    /**
     * Get idScadenze
     *
     * @return integer
     */
    public function getIdScadenze()
    {
        return $this->idScadenze;
    }
}
