<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Titolari
 *
 * @ORM\Table(name="msc_last_updates")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\LastUpdatesRepository")
 */
class LastUpdates
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
     * @ORM\Column(name="tabella", type="string", length=255)
     */
    private $tabella;

    
    /**
     * @var Datetime
     *
     * @ORM\Column(name="lastUpdate", type="datetime")
     */
    private $lastUpdate;
		
		
	

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
     * Set tabella
     *
     * @param string $tabella
     *
     * @return LastUpdates
     */
    public function setTabella($tabella)
    {
        $this->tabella = $tabella;

        return $this;
    }

    /**
     * Get tabella
     *
     * @return string
     */
    public function getTabella()
    {
        return $this->tabella;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return LastUpdates
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }
}
