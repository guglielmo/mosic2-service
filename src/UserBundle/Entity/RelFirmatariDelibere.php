<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelFirmatariDelibere
 *
 * @ORM\Table(name="msc_rel_firmatari_delibere")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelFirmatariDelibereRepository")
 */
class RelFirmatariDelibere
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
     * */
    private $idDelibere;
		
    /**
     * @var int
     *
     * @ORM\Column(name="id_firmatari", type="integer")
     * */
    private $idFirmatari;




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
     * @return RelFirmatariDelibere
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
     * Set idFirmatari
     *
     * @param integer $idFirmatari
     *
     * @return RelFirmatariDelibere
     */
    public function setIdFirmatari($idFirmatari)
    {
        $this->idFirmatari = $idFirmatari;

        return $this;
    }

    /**
     * Get idFirmatari
     *
     * @return integer
     */
    public function getIdFirmatari()
    {
        return $this->idFirmatari;
    }
}
