<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelUfficiDelibere
 *
 * @ORM\Table(name="msc_rel_uffici_delibere",indexes={@Index(name="id_delibere_idx", columns={"id_delibere"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelUfficiDelibereRepository")
 */
class RelUfficiDelibere
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
     * @ORM\Column(name="id_delibere", type="integer")
     */
    private $idDelibere;

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
     * Set idDelibere
     *
     * @param integer $idDelibere
     *
     * @return RelUfficiDelibere
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
     * Set idUffici
     *
     * @param integer $idUffici
     *
     * @return RelUfficiDelibere
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
