<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelAllegatiDelibere
 *
 * @ORM\Table(name="msc_rel_allegati_delibere",indexes={@Index(name="allegati_idx", columns={"id_delibere","id_allegati"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAllegatiDelibereRepository")
 */
class RelAllegatiDelibere
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
     * */
    private $idDelibere;
		
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
     * Set idDelibere
     *
     * @param integer $idDelibere
     *
     * @return RelAllegatiDelibere
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
     * Set idAllegati
     *
     * @param integer $idAllegati
     *
     * @return RelAllegatiDelibere
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
     * @return RelAllegatiDelibere
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
