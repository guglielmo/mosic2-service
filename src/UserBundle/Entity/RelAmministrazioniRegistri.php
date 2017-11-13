<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelAmministrazioniRegistri
 *
 * @ORM\Table(name="msc_rel_amministrazioni_registri",indexes={@Index(name="id_registri_idx", columns={"id_registri"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAmministrazioniRegistriRepository")
 */
class RelAmministrazioniRegistri
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
     * @ORM\Column(name="id_registri", type="integer")
     */
    private $idRegistri;

    /**
     * @var int
     *
     * @ORM\Column(name="id_amministrazioni", type="integer")
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
     * Set idRegistri
     *
     * @param integer $idRegistri
     *
     * @return RelAmministrazioniRegistri
     */
    public function setIdRegistri($idRegistri)
    {
        $this->idRegistri = $idRegistri;

        return $this;
    }

    /**
     * Get idRegistri
     *
     * @return integer
     */
    public function getIdRegistri()
    {
        return $this->idRegistri;
    }

    /**
     * Set idAmministrazioni
     *
     * @param integer $idAmministrazioni
     *
     * @return RelAmministrazioniRegistri
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
