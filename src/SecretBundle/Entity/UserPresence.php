<?php

namespace SecretBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPresence
 *
 * @ORM\Table(name="user_presence")
 * @ORM\Entity(repositoryClass="SecretBundle\Repository\UserPresenceRepository")
 */
class UserPresence
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
     * @var \DateTime
     *
     * @ORM\Column(name="presenceDate", type="datetime")
     */
    private $presenceDate;

    /**
     * @ORM\ManyToOne(targetEntity="UserInfo", inversedBy="userPresenceRelation")
     * @ORM\JoinColumn(name="userInfoId", referencedColumnName="id")
     */
    private $presence;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set presenceDate
     *
     * @param \DateTime $presenceDate
     *
     * @return UserPresence
     */
    public function setPresenceDate($presenceDate)
    {
        $this->presenceDate = $presenceDate;

        return $this;
    }

    /**
     * Get presenceDate
     *
     * @return \DateTime
     */
    public function getPresenceDate()
    {
        return $this->presenceDate;
    }


    /**
     * Set presence
     *
     * @param \SecretBundle\Entity\UserInfo $presence
     *
     * @return UserPresence
     */
    public function setPresence(\SecretBundle\Entity\UserInfo $presence = null)
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * Get presence
     *
     * @return \SecretBundle\Entity\UserInfo
     */
    public function getPresence()
    {
        return $this->presence;
    }
}
