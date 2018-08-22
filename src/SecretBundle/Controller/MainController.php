<?php

namespace SecretBundle\Controller;
use SecretBundle\Interfaces\CreateUserServiceInterface;
use SecretBundle\Interfaces\ClubCardNumberUniquenessServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class MainController extends Controller
{
    /**
     * @var clubCardNumberUniquenessService
     */
    private $clubCardNumberUniquenessService;

    /**
     * @var createUserServiceInterface
     */
    private $createUserServiceInterface;

    public function __construct(ClubCardNumberUniquenessServiceInterface $clubCardNumberUniquenessService, CreateUserServiceInterface $createUserService)
    {
        $this->clubCardNumberUniquenessService = $clubCardNumberUniquenessService;
        $this->createUserServiceInterface = $createUserService;
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
        if ($request->isXmlHttpRequest()) {
            $clubCardNumber = $request->query->get('clubCardNumber');
            $cardNumberExists = $this->clubCardNumberUniquenessService->checkUniqueness($clubCardNumber);
            return new JsonResponse(['cardNumberExists' => $cardNumberExists]);
        }
    }

    /**
     * @Route("/addUserForm", name="addUserForm")
     * @Method("GET")
     * @Template("@Secret/SecretView/addUserForm.html.twig")
     */
    public function addUserFormAction()
    {
        $form = $this->createUserServiceInterface->userInfoForm(['route' => 'createUser']);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/createUser", name="createUser")
     * @Method("POST")
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $req)
    {
        $form = $this->createUserServiceInterface->userInfoForm();

        $form->handleRequest($req);
        if ($form->isSubmitted()){
            if($req->request->get('create') && $form->isValid()){
                $data = $form->getData();
                $this->createUserServiceInterface->createUser($data);
            } else {
                $this->createUserServiceInterface->addUserErrorsHandler($form->getErrors(true));
            }

        return $this->redirectToRoute('addUserForm');
        }
    }

    /**
     * @Route("/editUserForm/{id}", name="editUserForm", defaults={"id"="0"} )
     * @Method("GET")
     * @Template("@Secret/SecretView/editUserForm.html.twig")
     * @param Request $req
     * @param int $id
     * @return array
     */
    public function editUserFormAction(Request $req, int $id): array
    {
        $form = $this->createUserServiceInterface->editUserForm(['route' => 'saveEditedUser'],$id);

        return [
            'userPicturePath' => $form['oldPicturePath'],
            'form' => $form['form']->createView()
        ];
    }

    /**
     * @Route("/saveEditedUser/{id}", name="saveEditedUser", defaults={"id"="0"})
     * @Method("POST")
     * @param Request $req
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveEditedUserAction(Request $req, int $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $oldPicturePath = $req->query->get('oldPicturePath');
        $form =  $form = $this->createUserServiceInterface->saveEditedUserForm([], $id, $oldPicturePath);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if($req->request->get('update') && $form->isValid()){
                $data = $form->getData();
                $this->createUserServiceInterface->setEditedPicturePath($data, $oldPicturePath);

                return $this->redirectToRoute('welcomePage');
            }
        }
    }

    /**
     * @Route("/showAllUsers", name="showAllUsers")
     * @Template("@Secret/SecretView/showAllUsers.html.twig")
     * @Method("GET")
     * @return array
     */
    public function showAllAction(): array
    {
        $form = $this->createUserServiceInterface->allUsersListForm(['route' => 'updateUser']);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/updateUser", name="updateUser")
     * @Method("POST")
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateUserAction(Request $req): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $form = $this->createUserServiceInterface->allUsersListForm();
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $id = $form->getData()->getName()->getId();
            return $this->redirectToRoute('editUserForm',["id" => $id]);
        }
    }



}
