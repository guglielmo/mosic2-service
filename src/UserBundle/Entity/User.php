<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\AttributeOverride;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\Column;

/**
 * User.
 *
 * @ORM\Table("fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    //const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
		
    /**
     * @var string
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="id_uffici", type="integer")
     */
    protected $idUffici;

    /**
     * @var string
     *
     * @ORM\Column(name="id_ruoli_cipe", type="integer")
     */
    protected $idRuoliCipe;


    /**
     * @var string
     * @ORM\Column(name="cessato_servizio", type="string", length=255)
     */
    protected $cessatoServizio;

    /**
     * @var string
     * @ORM\Column(name="ip", type="string", length=255)
     */
    protected $ip;

    /**
     * @var string
     * @ORM\Column(name="stazione", type="string", length=255)
     */
    protected $stazione;



    /**
     * @var datetime
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;


    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;


    public function __construct()
    {
        parent::__construct();

        // Add role
        $this->addRole("");
        $this->created = new \DateTime();
        $this->cessatoServizio = 0;
        $this->ip = "";
        $this->stazione = "";

    }



    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
		
		
    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set idUffici
     *
     * @param integer $idUffici
     *
     * @return User
     */
    public function setIdUffici($idUffici)
    {
        $this->idUffici = $idUffici;

        return $this;
    }

    /**
     * Get idUffici
     *
     * @return integer
     */
    public function getIdUffici()
    {
        return $this->idUffici;
    }

    /**
     * Set cessatoServizio
     *
     * @param string $cessatoServizio
     *
     * @return User
     */
    public function setCessatoServizio($cessatoServizio)
    {
        $this->cessatoServizio = $cessatoServizio;

        return $this;
    }

    /**
     * Get cessatoServizio
     *
     * @return string
     */
    public function getCessatoServizio()
    {
        return $this->cessatoServizio;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return User
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set stazione
     *
     * @param string $stazione
     *
     * @return User
     */
    public function setStazione($stazione)
    {
        $this->stazione = $stazione;

        return $this;
    }

    /**
     * Get stazione
     *
     * @return string
     */
    public function getStazione()
    {
        return $this->stazione;
    }

    /**
     * Set idRuoliCipe
     *
     * @param integer $idRuoliCipe
     *
     * @return User
     */
    public function setIdRuoliCipe($idRuoliCipe)
    {
        $this->idRuoliCipe = $idRuoliCipe;

        return $this;
    }

    /**
     * Get idRuoliCipe
     *
     * @return integer
     */
    public function getIdRuoliCipe()
    {
        return $this->idRuoliCipe;
    }
}
