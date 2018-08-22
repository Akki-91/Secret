<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 14.07.2018
 * Time: 10:42
 */

namespace SecretBundle\Service;
use Doctrine\ORM\EntityManagerInterface;
use SecretBundle\Interfaces\ClubCardNumberUniquenessServiceInterface;

class ClubCardNumberUniquenessService implements ClubCardNumberUniquenessServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ClubCardNumberUniquenessService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function checkUniqueness(int $clubCardNumber)
    {
        $repo = $this->entityManager->getRepository(UserInfo::class);
        $cardNumberExists = $repo->findByClubCardNumber($clubCardNumber);

        if(empty($cardNumberExists) === true ){
             return false;
        } else {
            return true;
        }
    }
}