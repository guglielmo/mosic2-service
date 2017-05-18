<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelUfficiPreCipe
 *
 * @ORM\Table(name="msc_rel_uffici_precipe")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelUfficiPreCipeRepository")
 */
class RelUfficiPreCipe
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
     * @ORM\Column(name="id_odg_precipe", type="integer")
     */
    private $idOdgPreCipe;

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
     * Set idOdgPreCipe
     *
     * @param integer $idOdgPreCipe
     *
     * @return RelUfficiPreCipe
     */
    public function setIdOdgPreCipe($idOdgPreCipe)
    {
        $this->idOdgPreCipe = $idOdgPreCipe;

        return $this;
    }

    /**
     * Get idOdgPreCipe
     *
     * @return integer
     */
    public function getIdOdgPreCipe()
    {
        return $this->idOdgPreCipe;
    }

    /**
     * Set idUffici
     *
     * @param integer $idUffici
     *
     * @return RelUfficiPreCipe
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
