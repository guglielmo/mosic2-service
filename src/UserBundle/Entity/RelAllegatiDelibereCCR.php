<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelAllegatiDelibereCCR
 *
 * @ORM\Table(name="msc_rel_allegati_delibere_ccr",indexes={@Index(name="allegati_idx", columns={"id_delibere_ccr","id_allegati"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAllegatiDelibereCCRRepository")
 */
class RelAllegatiDelibereCCR
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
     * @ORM\Column(name="id_delibere_ccr", type="integer")
     * */
    private $idDelibereCCR;
		
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
     * Set idDelibereCCR
     *
     * @param integer $idDelibereCCR
     *
     * @return RelAllegatiDelibereCCR
     */
    public function setIdDelibereCCR($idDelibereCCR)
    {
        $this->idDelibereCCR = $idDelibereCCR;

        return $this;
    }

    /**
     * Get idDelibereCCR
     *
     * @return integer
     */
    public function getIdDelibereCCR()
    {
        return $this->idDelibereCCR;
    }

    /**
     * Set idAllegati
     *
     * @param integer $idAllegati
     *
     * @return RelAllegatiDelibereCCR
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
