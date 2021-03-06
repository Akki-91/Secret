<?php

namespace SecretBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserInfo
 *
 * @ORM\Table(name="user_info")
 * @ORM\Entity(repositoryClass="SecretBundle\Repository\UserInfoRepository")
 * @UniqueEntity(
 *     "clubCardNumber",
 *      message="Numer karty już istnieje w systemie.",
 *      groups={"UserInfo"}
 * )
 */
class UserInfo
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
     * @Assert\Length(
     *     min=6,
     *     max=6,
     *     exactMessage = "Numer karty musi składać się z {{ limit }} cyfr",
     * )
     *
     * @ORM\Column(name="clubCardNumber", type="integer", unique=true)
     */
    private $clubCardNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paymentDate", type="datetime")
     */
    private $paymentDate;

    /**
     * @var int
     *
     * @ORM\Column(name="totalTrainingCount", type="integer")
     */
    private $totalTrainingCount;

    /**
     * @var int
     *
     * @ORM\Column(name="entriesLeft", type="integer")
     */
    private $entriesLeft;

    /**
     * @var int
     *
     * @ORM\Column(name="paymentAmmount", type="integer")
     */
    private $paymentAmmount;

    /**
     * @ORM\OneToMany(targetEntity="UserPresence", mappedBy="presence")
     * @Assert\Valid()
     */
    private $userPresenceRelation;

    /**
     * @ORM\OneToOne(targetEntity="UserExperience", mappedBy="experience", cascade={"persist", "merge"})
     * @Assert\Valid()
     */
    private $userExperienceRelation;

    /**
     * @var string
     *
     * @ORM\Column(name="picturePath", type="string", length=255)
     */
    private $picturePath;

    public function __construct() {
        $this->userPresenceRelation = new ArrayCollection();
    }

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
     * Set clubCardNumber
     *
     * @param integer $clubCardNumber
     *
     * @return UserInfo
     */
    public function setClubCardNumber($clubCardNumber)
    {
        $this->clubCardNumber = $clubCardNumber;

        return $this;
    }

    /**
     * Get clubCardNumber
     *
     * @return integer
     */
    public function getClubCardNumber()
    {
        return $this->clubCardNumber;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserInfo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     *
     * @return UserInfo
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set totalTrainingCount
     *
     * @param integer $totalTrainingCount
     *
     * @return UserInfo
     */
    public function setTotalTrainingCount($totalTrainingCount)
    {
        $this->totalTrainingCount = $totalTrainingCount;

        return $this;
    }

    /**
     * Get totalTrainingCount
     *
     * @return integer
     */
    public function getTotalTrainingCount()
    {
        return $this->totalTrainingCount;
    }

    /**
     * Set entriesLeft
     *
     * @param integer $entriesLeft
     *
     * @return UserInfo
     */
    public function setEntriesLeft($entriesLeft)
    {
        $this->entriesLeft = $entriesLeft;

        return $this;
    }

    /**
     * Get entriesLeft
     *
     * @return integer
     */
    public function getEntriesLeft()
    {
        return $this->entriesLeft;
    }

    /**
     * Add userPresenceRelation
     *
     * @param \SecretBundle\Entity\UserPresence $userPresenceRelation
     *
     * @return UserInfo
     */
    public function addUserPresenceRelation(\SecretBundle\Entity\UserPresence $userPresenceRelation)
    {
        $this->userPresenceRelation[] = $userPresenceRelation;

        return $this;
    }

    /**
     * Remove userPresenceRelation
     *
     * @param \SecretBundle\Entity\UserPresence $userPresenceRelation
     */
    public function removeUserPresenceRelation(\SecretBundle\Entity\UserPresence $userPresenceRelation)
    {
        $this->userPresenceRelation->removeElement($userPresenceRelation);
    }

    /**
     * Get userPresenceRelation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserPresenceRelation()
    {
        return $this->userPresenceRelation;
    }

    /**
     * Set paymentAmmount
     *
     * @param integer $paymentAmmount
     *
     * @return UserInfo
     */
    public function setPaymentAmmount($paymentAmmount)
    {
        $this->paymentAmmount = $paymentAmmount;

        return $this;
    }

    /**
     * Get paymentAmmount
     *
     * @return integer
     */
    public function getPaymentAmmount()
    {
        return $this->paymentAmmount;
    }
    
    /**
     * Set picturePath
     *
     * @param string $picturePath
     *
     * @return UserInfo
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    /**
     * Get picturePath
     *
     * @return string
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * Set userExperienceRelation
     *
     * @param \SecretBundle\Entity\UserExperience $userExperienceRelation
     *
     * @return UserInfo
     */
    public function setUserExperienceRelation(\SecretBundle\Entity\UserExperience $userExperienceRelation = null)
    {
        $this->userExperienceRelation = $userExperienceRelation;

        return $this;
    }

    /**
     * Get userExperienceRelation
     *
     * @return \SecretBundle\Entity\UserExperience
     */
    public function getUserExperienceRelation()
    {
        return $this->userExperienceRelation;
    }
}
