<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelTagsRegistri
 *
 * @ORM\Table(name="msc_rel_tags_registri")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelTagsRegistriRepository")
 */
class RelTagsRegistri
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
     */
    private $idRegistri;

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
     * Set idRegistri
     *
     * @param integer $idRegistri
     *
     * @return RelTagsRegistri
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
     * Set idTags
     *
     * @param integer $idTags
     *
     * @return RelTagsRegistri
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
