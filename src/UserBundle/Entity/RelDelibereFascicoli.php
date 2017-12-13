<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelDelibereFascicoli
 *
 * @ORM\Table(name="msc_rel_delibere_fascicoli",indexes={@Index(name="id_delibere_idx", columns={"id_delibere"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelDelibereFascicoliRepository")
 */
class RelDelibereFascicoli
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
     * @ORM\Column(name="id_fascicoli", type="integer")
     * */
    private $idFascicoli;






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
     * @return RelDelibereFascicoli
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
     * Set idFascicoli
     *
     * @param integer $idFascicoli
     *
     * @return RelDelibereFascicoli
     */
    public function setIdFascicoli($idFascicoli)
    {
        $this->idFascicoli = $idFascicoli;

        return $this;
    }

    /**
     * Get idFascicoli
     *
     * @return integer
     */
    public function getIdFascicoli()
    {
        return $this->idFascicoli;
    }
}
