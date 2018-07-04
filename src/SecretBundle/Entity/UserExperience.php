<?php

namespace SecretBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserExperience
 *
 * @ORM\Table(name="user_experience")
 * @ORM\Entity(repositoryClass="SecretBundle\Repository\UserExperienceRepository")
 */
class UserExperience
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
     * @ORM\Column(name="belt", type="integer")
     */
    private $belt;

    /**
     * @var int
     * @ORM\Column(name="stripes", type="integer")
     */
    private $stripes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="promotionDate", type="date")
     */
    private $promotionDate;

    /**
     * @var int
     *
     * @ORM\Column(name="trainingsCountOnPromotionDay", type="integer")
     */
    private $trainingsCountOnPromotionDay;

    /**
     * @ORM\ManyToOne(targetEntity="UserInfo", inversedBy="userExperienceRelation")
     * @ORM\JoinColumn(name="userInfoIdInExp", referencedColumnName="id")
     */
    private $experience;


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
     * Set belt
     *
     * @param integer $belt
     *
     * @return UserExperience
     */
    public function setBelt($belt)
    {
        $this->belt = $belt;

        return $this;
    }

    /**
     * Get belt
     *
     * @return int
     */
    public function getBelt()
    {
        return $this->belt;
    }

    /**
     * Set stripes
     *
     * @param integer $stripes
     *
     * @return UserExperience
     */
    public function setStripes($stripes)
    {
        $this->stripes = $stripes;

        return $this;
    }

    /**
     * Get stripes
     *
     * @return int
     */
    public function getStripes()
    {
        return $this->stripes;
    }

    /**
     * Set promotionDate
     *
     * @param \DateTime $promotionDate
     *
     * @return UserExperience
     */
    public function setPromotionDate($promotionDate)
    {
        $this->promotionDate = $promotionDate;

        return $this;
    }

    /**
     * Get promotionDate
     *
     * @return \DateTime
     */
    public function getPromotionDate()
    {
        return $this->promotionDate;
    }

    /**
     * Set trainingsCountOnPromotionDay
     *
     * @param integer $trainingsCountOnPromotionDay
     *
     * @return UserExperience
     */
    public function setTrainingsCountOnPromotionDay($trainingsCountOnPromotionDay)
    {
        $this->trainingsCountOnPromotionDay = $trainingsCountOnPromotionDay;

        return $this;
    }

    /**
     * Get trainingsCountOnPromotionDay
     *
     * @return int
     */
    public function getTrainingsCountOnPromotionDay()
    {
        return $this->trainingsCountOnPromotionDay;
    }

    /**
     * Set experience
     *
     * @param \SecretBundle\Entity\UserInfo $experience
     *
     * @return UserExperience
     */
    public function setExperience(\SecretBundle\Entity\UserInfo $experience = null)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return \SecretBundle\Entity\UserInfo
     */
    public function getExperience()
    {
        return $this->experience;
    }
}
