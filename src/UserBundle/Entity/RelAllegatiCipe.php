<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelAllegatiCipe
 *
 * @ORM\Table(name="msc_rel_allegati_cipe",indexes={@Index(name="allegati_idx", columns={"id_cipe","id_allegati"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAllegatiCipeRepository")
 */
class RelAllegatiCipe
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
     * @ORM\Column(name="id_cipe", type="integer")
     * */
    private $idCipe;
		
    /**
     * @var int
     *
     * @ORM\Column(name="id_allegati", type="integer")
     * */
    private $idAllegati;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;



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
     * Set idCipe
     *
     * @param integer $idCipe
     *
     * @return RelAllegatiCipe
     */
    public function setIdCipe($idCipe)
    {
        $this->idCipe = $idCipe;

        return $this;
    }

    /**
     * Get idCipe
     *
     * @return integer
     */
    public function getIdCipe()
    {
        return $this->idCipe;
    }

    /**
     * Set idAllegati
     *
     * @param integer $idAllegati
     *
     * @return RelAllegatiCipe
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

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return RelAllegatiCipe
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
