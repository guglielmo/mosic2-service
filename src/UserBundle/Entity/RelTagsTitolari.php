<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelTagsTitolari
 *
 * @ORM\Table(name="msc_rel_tags_titolari")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelTagsTitolariRepository")
 */
class RelTagsTitolari
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
    private $idTitolari;

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
     * Set idTitolari
     *
     * @param integer $idTitolari
     *
     * @return RelTagsTitolari
     */
    public function setIdTitolari($idTitolari)
    {
        $this->idTitolari = $idTitolari;

        return $this;
    }

    /**
     * Get idTitolari
     *
     * @return integer
     */
    public function getIdTitolari()
    {
        return $this->idTitolari;
    }

    /**
     * Set idTags
     *
     * @param integer $idTags
     *
     * @return RelTagsTitolari
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
