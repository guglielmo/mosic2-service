<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Allegati
 *
 * @ORM\Table(name="msc_allegati")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\AllegatiRepository")
 */
class Allegati
{
	
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="data", type="datetime")
     */
    private $data;

    /**
     * @ORM\Column(name="file", type="string", length=255)
     * */
    private $file;


    /**
     * @ORM\Column(name="escluso", type="integer")
     * */
    private $escluso;


    public function __construct() {
        $this->escluso = 0;
    }



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
     * Set data
     *
     * @param \DateTime $data
     *
     * @return Allegati
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Allegati
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getEscluso()
    {
        return $this->escluso;
    }

    /**
     * @param mixed $escluso
     */
    public function setEscluso($escluso)
    {
        $this->escluso = $escluso;
    }


}
