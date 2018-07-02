<?php

namespace SecretBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use SecretBundle\Entity\UserInfo;
use SecretBundle\Entity\UserExperience;

use SecretBundle\Form\AllUsersListForm;

class UserExperienceController extends Controller
{

    /**
     * @var userInfo
     */
    private $userInfo;

    /**
     * @var userExperience
     */
    private $userExperience;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserInfo $userInfo, EntityManagerInterface $entityManager, UserExperience $userExperience)
    {
        $this->userInfo = $userInfo;
        $this->userExperience = $userExperience;
        $this->em = $entityManager;
    }

    /**
     * @Route("/userToPromote", name="userToPromote")
     * @Template("@Secret/SecretView/userToPromote.html.twig")
     */
    public function userToPromoteAction()
    {
        $user = $this->userInfo;
        $form = $this->createForm(AllUsersListForm::class, $user, [
            'action' => $this->generateUrl('beltPromotion')
        ]);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/beltPromotion", name="beltPromotion")
     * @Template("@Secret/SecretView/beltPromotion.html.twig")
     */
    public function beltPromotionAction()
    {

    }
}
