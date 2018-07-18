<?php

namespace SecretBundle\Controller;

use SecretBundle\SecretBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;

use SecretBundle\Service\ClubCardNumberUniquenessService;

use SecretBundle\Entity\UserInfo;
use SecretBundle\Entity\UserExperience;

use SecretBundle\Form\UserInfoForm;
use SecretBundle\Form\AllUsersListForm;



class MainController extends Controller
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
     * @var clubCardNumberUniquenessService
     */
    private $clubCardNumberUniquenessService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserInfo $userInfo, EntityManagerInterface $entityManager, UserExperience $userExperience, ClubCardNumberUniquenessService $clubCardNumberUniquenessService)
    {
        $this->userInfo = $userInfo;
        $this->userExperience = $userExperience;
        $this->em = $entityManager;
        $this->clubCardNumberUniquenessService = $clubCardNumberUniquenessService;
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @return integer
     */
    private function countEntries($paymentAmmount)
    {
        switch($paymentAmmount){
            case 0:
                return 4;
                break;
            case 1:
                return 8;
                break;
            case 2:
                return 12;
                break;
            default:
                return 0;
        }
    }



    /**
     * @Route("/", name="welcomePage")
     * @Template("@Secret/SecretView/welcomePage.html.twig")
     */
    public function welcomePageAction()
    {
    }

    /**
     * @Route("/checkClubCardNumber", name="checkClubCardNumber")
     */
    public function checkClubCardNumber(Request $request)
    {
//        if ($request->isXmlHttpRequest()) {
//            $clubCardNumber = $request->query->get('clubCardNumber');
//            $cardNumberExists = $this->clubCardNumberUniquenessService->checkUniqueness($clubCardNumber);
//            return new JsonResponse(array('cardNumberExists' => true));
//        }

        if ($request->isXmlHttpRequest()) {
            $clubCardNumber = $request->query->get('clubCardNumber');
            $repo = $this->em->getRepository(UserInfo::class);
            $cardNumberExists = $repo->findOneByClubCardNumber($clubCardNumber);

            if($cardNumberExists instanceof UserInfo){
                return new JsonResponse(array('cardNumberExists' => true));
            }

            return new JsonResponse(array('cardNumberExists' => false));
        }
    }

    /**
     * @Route("/addUserForm", name="addUserForm")
     * @Method("GET")
     * @Template("@Secret/SecretView/addUserForm.html.twig")
     */
    public function addUserFormAction()
    {
        $userInfo = $this->userInfo;
        $userExp = $this->userExperience;

        $userInfo->getUserExperienceRelation()->add($userExp);

        $form = $this->createForm(UserInfoForm::class, $userInfo,[
           'action' => $this->generateUrl('createUser')
        ]);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/createUser", name="createUser")
     * @Method("POST")
     */
    public function createAction(Request $req)
    {
        $date = new \DateTime("now");
        $userInfo = $this->userInfo;
        $userExp = $this->userExperience;

        $userInfo->getUserExperienceRelation()->add($userExp);

        $form = $this->createForm(UserInfoForm::class,$userInfo);

        $form->handleRequest($req);
        if ($form->isSubmitted()){
            if($req->request->get('create') && $form->isValid()){
                $file = $userInfo->getPicturePath();
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $directory = $this->container->getParameter('kernel.root_dir') . '/../web/usersPictures';
                $file->move($directory ,$fileName);

                $userInfo->setPicturePath($fileName);
                $userInfo->setTotalTrainingCount(0);
                $userInfo->setEntriesLeft($this->countEntries($userInfo->getPaymentAmmount()));

                $userExp->setPromotionDate($date);
                $userExp->setTrainingsCountOnPromotionDay(0);
                $userExp->setExperience($userInfo);

                $this->em->persist($userInfo);
                $this->em->persist($userExp);
                $this->em->flush();

                $this->addFlash('userAdded', "Użytkownik " . $userInfo->getName(). " został pomyślnie zarejestrowany." );
                return $this->redirectToRoute('addUserForm');

            } else {
                $errorMessages = [];
                foreach ($form->getErrors(true) as $key => $error) {
                    $errorMessages[] = ['key' => $key, 'msg' => $error->getMessage()];
                }

                if(count($errorMessages) > 0){
                    $this->addFlash('errorMessages', $errorMessages );
                }

                return $this->redirectToRoute('addUserForm');
            }
        }
    }

    /**
     * @Route("/editUserForm/{id}", name="editUserForm", defaults={"id"="0"} )
     * @Method("GET")
     * @Template("@Secret/SecretView/editUserForm.html.twig")
     */
    public function editUserFormAction(Request $req, int $id)
    {
        $repo = $this->em->getRepository(UserInfo::class);
        $userInfo = $repo->find($id);
        $userExp = $this->userExperience;
        $oldPicturePath = $userInfo->getPicturePath();

        $userPicturePath = $this->getParameter('kernel.root_dir').'/../web/usersPictures/'.$userInfo->getPicturePath();
        $userInfo->setPicturePath(new File($userPicturePath));
        $userInfo->getUserExperienceRelation()->add($userExp);

        $form = $this->createForm(UserInfoForm::class, $userInfo,[
            'action' => $this->generateUrl('saveEditedUser',['id' => $id, 'oldPicturePath' => $oldPicturePath]),
            'mappingOn' => false,
        ]);

        return [
            'userPicturePath' => $oldPicturePath,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/saveEditedUser/{id}", name="saveEditedUser", defaults={"id"="0"})
     * @Method("POST")
     */
    public function saveEditedUserAction(Request $req, int $id)
    {
        $repo = $this->em->getRepository(UserInfo::class);
        $userInfo = $repo->find($id);
        $oldPicturePath = $req->query->get('oldPicturePath');
        $userPicturePath = $this->getParameter('kernel.root_dir').'/../web/usersPictures/'. $oldPicturePath;
        $userInfo->setPicturePath(new File($userPicturePath));

        $form = $this->createForm(UserInfoForm::class, $userInfo);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if($req->request->get('update') && $form->isValid()){
                if($userInfo->getPicturePath() === null){
                    $userInfo->setPicturePath($oldPicturePath);
                } else {
                    $file = $userInfo->getPicturePath();
                    $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                    $directory = $this->container->getParameter('kernel.root_dir') . '/../web/usersPictures';
                    $file->move($directory ,$fileName);
                    $userInfo->setPicturePath($fileName);
                }

            $this->em->persist($userInfo);
            $this->em->flush();
            return $this->redirectToRoute('welcomePage');
            }
        }

    }

    /**
     * @Route("/showAllUsers", name="showAllUsers")
     * @Template("@Secret/SecretView/showAllUsers.html.twig")
     * @Method("GET")
     */
    public function showAllAction(Request $req)
    {
        $user = $this->userInfo;
        $form = $this->createForm(AllUsersListForm::class, $user, [
            'action' => $this->generateUrl('updateUser')
        ]);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/updateUser", name="updateUser")
     * @Method("POST")
     */
    public function updateUserAction(Request $req)
    {
        $user = $this->userInfo;
        $form = $this->createForm(AllUsersListForm::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $id = $user->getName()->getId();
            return $this->redirectToRoute('editUserForm',["id" => $id]);
        }
    }



}
