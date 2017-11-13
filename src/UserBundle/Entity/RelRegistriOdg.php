<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelRegistriOdg
 *
 * @ORM\Table(name="msc_rel_registri_odg",indexes={@Index(name="id_odg_idx", columns={"id_odg"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelRegistriOdgRepository")
 */
class RelRegistriOdg
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
     * @ORM\Column(name="id_odg", type="integer")
     */
    private $idOdg;

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
     * Set idOdg
     *
     * @param integer $idOdg
     *
     * @return RelRegistriOdg
     */
    public function setIdOdg($idOdg)
    {
        $this->idOdg = $idOdg;

        return $this;
    }

    /**
     * Get idOdg
     *
     * @return integer
     */
    public function getIdOdg()
    {
        return $this->idOdg;
    }

    /**
     * Set idRegistri
     *
     * @param integer $idRegistri
     *
     * @return RelRegistriOdg
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
