<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 14.07.2018
 * Time: 10:42
 */

namespace SecretBundle\Service;
use Doctrine\ORM\EntityManagerInterface;
use SecretBundle\Entity\UserInfo;

class ClubCardNumberUniquenessService
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

//    public function checkUniqueness(int $clubCardNumber)
//    {
//        $repo = $this->entityManager->getRepository(UserInfo::class);
//        $cardNumberExists = $repo->findByClubCardNumber($clubCardNumber);
//
//        //wyskakuje mi z AJAX'a przez ten return. Nie wiem jak przekazac dane do kontrolki...
//        if(empty($cardNumberExists) === true ){
//            var_dump('null');
//             json_encode(false);
//             return false;
//            return new JsonResponse(array('cardNumberExists' => false));
//        } else {
//            var_dump(' not null');
//             json_encode(true);
//        }
//    }
}