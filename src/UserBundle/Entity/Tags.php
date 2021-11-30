<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tags
 *
 * @ORM\Table(name="msc_tags")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\TagsRepository")
 */
class Tags
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
     * @var string
     *
     * @ORM\Column(name="denominazione", type="string", length=255)
     */
    private $denominazione;

    //MODIFICA MOSIC 3.0 del 17/06/2020
    /**
     * @var int
     *
     * @ORM\Column(name="disattivo", type="integer")
     */
    private $disattivo;


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
     * Set denominazione
     *
     * @param string $denominazione
     *
     * @return Tags
     */
    public function setDenominazione($denominazione)
    {
        $this->denominazione = $denominazione;

        return $this;
    }

    /**
     * Get denominazione
     *
     * @return string
     */
    public function getDenominazione()
    {
        return $this->denominazione;
    }

    /**
     * @return int
     */
    public function getDisattivo()
    {
        return $this->disattivo;
    }

    /**
     * @param int $disattivo
     */
    public function setDisattivo($disattivo)
    {
        $this->disattivo = $disattivo;
    }



}
