<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelTagsFascicoli
 *
 * @ORM\Table(name="msc_rel_tags_fascicoli",indexes={@Index(name="id_fascicoli_idx", columns={"id_fascicoli"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelTagsFascicoliRepository")
 */
class RelTagsFascicoli
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
     * @ORM\Column(name="id_fascicoli", type="integer")
     */
    private $idFascicoli;

    /**
     * @var int
     *
     * @ORM\Column(name="id_tags", type="integer")
     */
    private $idTags;


 


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
     * Set idFascicoli
     *
     * @param integer $idFascicoli
     *
     * @return RelTagsFascicoli
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

    /**
     * Set idTags
     *
     * @param integer $idTags
     *
     * @return RelTagsFascicoli
     */
    public function setIdTags($idTags)
    {
        $this->idTags = $idTags;

        return $this;
    }

    /**
     * Get idTags
     *
     * @return integer
     */
    public function getIdTags()
    {
        return $this->idTags;
    }
}
