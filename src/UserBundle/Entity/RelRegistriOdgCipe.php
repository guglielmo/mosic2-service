<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelRegistriOdgCipe
 *
 * @ORM\Table(name="msc_rel_registri_odg_cipe",indexes={@Index(name="id_odg_cipe_idx", columns={"id_odg_cipe"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelRegistriOdgCipeRepository")
 */
class RelRegistriOdgCipe
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
     * @ORM\Column(name="id_odg_cipe", type="integer")
     */
    private $idOdgCipe;

    /**
     * @var int
     *
     * @ORM\Column(name="id_registri", type="integer")
     * */
    private $idRegistri;




  



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
     * Set idOdgCipe
     *
     * @param integer $idOdgCipe
     *
     * @return RelRegistriOdgCipe
     */
    public function setIdOdgCipe($idOdgCipe)
    {
        $this->idOdgCipe = $idOdgCipe;

        return $this;
    }

    /**
     * Get idOdgCipe
     *
     * @return integer
     */
    public function getIdOdgCipe()
    {
        return $this->idOdgCipe;
    }

    /**
     * Set idRegistri
     *
     * @param integer $idRegistri
     *
     * @return RelRegistriOdgCipe
     */
    public function setIdRegistri($idRegistri)
    {
        $this->idRegistri = $idRegistri;

        return $this;
    }

    /**
     * Get idRegistri
     *
     * @return integer
     */
    public function getIdRegistri()
    {
        return $this->idRegistri;
    }
}
