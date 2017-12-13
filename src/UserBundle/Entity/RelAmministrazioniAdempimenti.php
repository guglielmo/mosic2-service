<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * RelAmministrazioniAdempimenti
 *
 * @ORM\Table(name="msc_rel_amministrazioni_adempimenti",indexes={@Index(name="id_adempimenti_idx", columns={"id_adempimenti"})})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelAmministrazioniAdempimentiRepository")
 */
class RelAmministrazioniAdempimenti
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
     * @ORM\Column(name="id_adempimenti", type="integer")
     */
    private $idAdempimenti;

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
     * Set idAdempimenti
     *
     * @param integer $idAdempimenti
     *
     * @return RelAmministrazioniAdempimenti
     */
    public function setIdAdempimenti($idAdempimenti)
    {
        $this->idAdempimenti = $idAdempimenti;

        return $this;
    }

    /**
     * Get idAdempimenti
     *
     * @return integer
     */
    public function getIdAdempimenti()
    {
        return $this->idAdempimenti;
    }

    /**
     * Set idAmministrazioni
     *
     * @param integer $idAmministrazioni
     *
     * @return RelAmministrazioniAdempimenti
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
