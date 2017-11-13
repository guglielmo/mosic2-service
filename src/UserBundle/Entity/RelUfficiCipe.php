<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelUfficiCipe
 *
 * @ORM\Table(name="msc_rel_uffici_cipe",indexes={@Index(name="id_odg_cipe_idx", columns={"id_odg_cipe"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelUfficiCipeRepository")
 */
class RelUfficiCipe
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
     * @ORM\Column(name="id_uffici", type="integer")
     * */
    private $idUffici;






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
     * @return RelUfficiCipe
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
     * Set idUffici
     *
     * @param integer $idUffici
     *
     * @return RelUfficiCipe
     */
    public function setIdUffici($idUffici)
    {
        $this->idUffici = $idUffici;

        return $this;
    }

    /**
     * Get idUffici
     *
     * @return integer
     */
    public function getIdUffici()
    {
        return $this->idUffici;
    }
}
