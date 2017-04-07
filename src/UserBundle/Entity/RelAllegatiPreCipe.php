<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelAllegatiPreCipe
 *
 * @ORM\Table(name="msc_rel_allegati_precipe")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAllegatiPreCipeRepository")
 */
class RelAllegatiPreCipe
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
     * @ORM\Column(name="id_precipe", type="integer")
     * */
    private $idPreCipe;
		
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
     * Set idPreCipe
     *
     * @param integer $idPreCipe
     *
     * @return RelAllegatiPreCipe
     */
    public function setIdPreCipe($idPreCipe)
    {
        $this->idPreCipe = $idPreCipe;

        return $this;
    }

    /**
     * Get idPreCipe
     *
     * @return integer
     */
    public function getIdPreCipe()
    {
        return $this->idPreCipe;
    }

    /**
     * Set idAllegati
     *
     * @param integer $idAllegati
     *
     * @return RelAllegatiPreCipe
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
     * @return RelAllegatiPreCipe
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
