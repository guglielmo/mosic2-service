<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelAllegatiRegistri
 *
 * @ORM\Table(name="msc_rel_allegati_registri",indexes={@Index(name="allegati_idx", columns={"id_registri","id_allegati"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAllegatiRegistriRepository")
 */
class RelAllegatiRegistri
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
     * @ORM\Column(name="id_registri", type="integer")
     * */
    private $idRegistri;
		
    /**
     * @var int
     *
     * @ORM\Column(name="id_allegati", type="integer")
     * */
    private $idAllegati;
		



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
     * Set idRegistri
     *
     * @param integer $idRegistri
     *
     * @return RelAllegatiRegistri
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

    /**
     * Set idAllegati
     *
     * @param integer $idAllegati
     *
     * @return RelAllegatiRegistri
     */
    public function setIdAllegati($idAllegati)
    {
        $this->idAllegati = $idAllegati;

        return $this;
    }

    /**
     * Get idAllegati
     *
     * @return integer
     */
    public function getIdAllegati()
    {
        return $this->idAllegati;
    }
}
