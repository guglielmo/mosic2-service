<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelAmministrazioniFascicoli
 *
 * @ORM\Table(name="msc_rel_amministrazioni_fascicoli",indexes={@Index(name="id_fascicoli_idx", columns={"id_fascicoli"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAmministrazioniFascicoliRepository")
 */
class RelAmministrazioniFascicoli
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
     * @ORM\Column(name="id_fascicoli", type="integer")
     * @ORM\ManyToOne(targetEntity="Fascicoli", inversedBy="id")
     */
    private $idFascicoli;

    /**
     * @var int
     *
     * @ORM\Column(name="id_amministrazioni", type="integer")
     * @ORM\ManyToOne(targetEntity="Amministrazioni", inversedBy="id")
     * */
    private $idAmministrazioni;




   

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
     * Set idFascicoli
     *
     * @param integer $idFascicoli
     *
     * @return RelAmministrazioniFascicoli
     */
    public function setIdFascicoli($idFascicoli)
    {
        $this->idFascicoli = $idFascicoli;

        return $this;
    }

    /**
     * Get idFascicoli
     *
     * @return integer
     */
    public function getIdFascicoli()
    {
        return $this->idFascicoli;
    }

    /**
     * Set idAmministrazioni
     *
     * @param integer $idAmministrazioni
     *
     * @return RelAmministrazioniFascicoli
     */
    public function setIdAmministrazioni($idAmministrazioni)
    {
        $this->idAmministrazioni = $idAmministrazioni;

        return $this;
    }

    /**
     * Get idAmministrazioni
     *
     * @return integer
     */
    public function getIdAmministrazioni()
    {
        return $this->idAmministrazioni;
    }
}
