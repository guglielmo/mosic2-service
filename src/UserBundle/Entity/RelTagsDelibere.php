<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelTagsDelibere
 *
 * @ORM\Table(name="msc_rel_tags_delibere")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelTagsDelibereRepository")
 */
class RelTagsDelibere
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
     * Set idDelibere
     *
     * @param integer $idDelibere
     *
     * @return RelTagsDelibere
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
     * Set idTags
     *
     * @param integer $idTags
     *
     * @return RelTagsDelibere
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
