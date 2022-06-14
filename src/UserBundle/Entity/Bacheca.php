<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bacheca
 *
 * @ORM\Table(name="msc_bacheca")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\BachecaRepository")
 */
class Bacheca {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="dipe", type="integer")
     */
    private $dipe;

    /**
     * @ORM\Column(name="mef", type="integer")
     */
    private $mef;

    /**
     * @ORM\Column(name="firme", type="integer")
     */
    private $firme;

    /**
     * @ORM\Column(name="cc", type="integer")
     */
    private $cc;

    /**
     * @ORM\Column(name="gu", type="integer")
     */
    private $gu;

    /**
     * @ORM\Column(name="anno", type="integer")
     */
    private $anno;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDipe() {
        return $this->dipe;
    }

    /**
     * @param mixed $dipe
     */
    public function setDipe($dipe) {
        $this->dipe = $dipe;
    }

    /**
     * @return mixed
     */
    public function getMef() {
        return $this->mef;
    }

    /**
     * @param mixed $mef
     */
    public function setMef($mef) {
        $this->mef = $mef;
    }

    /**
     * @return mixed
     */
    public function getFirme() {
        return $this->firme;
    }

    /**
     * @param mixed $firme
     */
    public function setFirme($firme) {
        $this->firme = $firme;
    }

    /**
     * @return mixed
     */
    public function getCc() {
        return $this->cc;
    }

    /**
     * @param mixed $cc
     */
    public function setCc($cc) {
        $this->cc = $cc;
    }

    /**
     * @return mixed
     */
    public function getGu() {
        return $this->gu;
    }

    /**
     * @param mixed $gu
     */
    public function setGu($gu) {
        $this->gu = $gu;
    }

    /**
     * @return mixed
     */
    public function getAnno() {
        return $this->anno;
    }

    /**
     * @param mixed $anno
     */
    public function setAnno($anno) {
        $this->anno = $anno;
    }

}
