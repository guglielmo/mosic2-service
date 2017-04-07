<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * User.
 *
 * @ORM\Table("fos_group")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */



class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="codice", type="string", length=255)
     */
    protected $codice;


    /**
     * Set codice
     *
     * @param string $codice
     *
     * @return Group
     */
    public function setCodice($codice)
    {
        $this->codice = $codice;

        return $this;
    }

    /**
     * Get codice
     *
     * @return string
     */
    public function getCodice()
    {
        return $this->codice;
    }
}
