<?php

namespace SecretBundle\Service;

use SecretBundle\Interfaces\CreateUserServiceInterface;
use SecretBundle\Factories\UsersFactory;
use SecretBundle\Form\UserInfoForm;
use SecretBundle\Form\AllUsersListForm;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File as File;

class CreateUserService implements CreateUserServiceInterface
{
    /**
     * @var formFactory
     */
    private $formFactory;

    /**
     * @var router
     */
    private $router;

    /**
     * @var kernelRootDir
     */
    private $kernelRootDir;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CreateUserService constructor.
     * @param string $kernelRootDir
     * @param SessionInterface $session
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $kernelRootDir, SessionInterface $session, FormFactoryInterface $formFactory, RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->kernelRootDir = $kernelRootDir;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->em = $entityManager;
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function generateUniqueFileName(): string
    {
        return md5(uniqid());
    }

    /**
     * @param int $paymentAmmount
     * @return int
     */
    public function countEntries(int $paymentAmmount): int
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
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function userInfoForm(array $options = []): \Symfony\Component\Form\FormInterface
    {
        $userInfo = UsersFactory::create('UserInfo');
        $userExp =  UsersFactory::create('UserExperience');

        $userInfo->setUserExperienceRelation($userExp);

        if(!empty($options)){
            $options = ['action' => $this->router->generate($options['route'],[],UrlGeneratorInterface::ABSOLUTE_PATH)];
        }

        return $this->formFactory->create(UserInfoForm::class, $userInfo, $options);
    }

    /**
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function allUsersListForm(array $options = []): \Symfony\Component\Form\FormInterface
    {
        $userInfo = UsersFactory::create('UserInfo');

        if(!empty($options)){
            $options = ['action' => $this->router->generate($options['route'],[],UrlGeneratorInterface::ABSOLUTE_PATH)];
        }

        return $this->formFactory->create(AllUsersListForm::class, $userInfo, $options);
    }

    /**
     * @param array $options
     * @param int $id
     * @return array
     */
    public function editUserForm(array $options = [], int $id): array
    {
        $repo = $this->em->getRepository(\SecretBundle\Entity\UserInfo::class);
        $userInfo = $repo->find($id);
        $userExp = UsersFactory::create('UserExperience');
        $oldPicturePath = $userInfo->getPicturePath();

        $userPicturePath = $this->kernelRootDir . '/../web/usersPictures/'.$userInfo->getPicturePath();
        $userInfo->setPicturePath(new File($userPicturePath));
        $userInfo->getUserExperienceRelation($userExp);

        $form = $this->formFactory->create(UserInfoForm::class, $userInfo,[
            'action' => $this->router->generate($options['route'],['id' => $id, 'oldPicturePath' => $oldPicturePath],UrlGeneratorInterface::ABSOLUTE_PATH),
            'mappingOn' => false,
        ]);

        return ['form' => $form, 'oldPicturePath' => $oldPicturePath];
    }

    /**
     * @param array $options
     * @param int $id
     * @return array
     */
    public function saveEditedUserForm(array $options = [], int $id, $oldPicturePath)
    {
        $repo = $this->em->getRepository(\SecretBundle\Entity\UserInfo::class);
        $userInfo = $repo->find($id);

        $userPicturePath = $this->kernelRootDir . '/../web/usersPictures/'. $oldPicturePath;
        $userInfo->setPicturePath(new File($userPicturePath));

        return $this->formFactory->create(UserInfoForm::class, $userInfo, $options);
    }

    /**
     * @param $data
     * @param $oldPicturePath
     */
    public function setEditedPicturePath($data, $oldPicturePath): void
    {
        if($data->getPicturePath() === null){
            $data->setPicturePath($oldPicturePath);
        } else {
            $file = $data->getPicturePath();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $directory = $this->kernelRootDir . '/../web/usersPictures';
            $file->move($directory ,$fileName);
            $data->setPicturePath($fileName);
        }

        $this->em->persist($data);
        $this->em->flush();
    }

    /**
     * @param \SecretBundle\Entity\UserInfo $data
     */
    public function createUser(\SecretBundle\Entity\UserInfo $data): void
    {   $date = new \DateTime("now");
        $userExp = $data->getUserExperienceRelation();

        $file = $data->getPicturePath();
        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        $directory = $this->kernelRootDir . '/../web/usersPictures';

        $file->move($directory ,$fileName);

        $data->setPicturePath($fileName);
        $data->setTotalTrainingCount(0);
        $data->setEntriesLeft($this->countEntries($data->getPaymentAmmount()));

        $userExp->setPromotionDate($date);
        $userExp->setTrainingsCountOnPromotionDay(0);
        $userExp->setExperience($data);

        $this->em->persist($data);
        $this->em->flush();

        $this->session->getFlashBag()->add('userAdded', "Użytkownik " . $data->getName(). " został pomyślnie zarejestrowany." );
    }

    /**
     * @param $errors
     */
    public function addUserErrorsHandler($errors): void
    {
        $errorMessages = [];
        foreach ($errors as $key => $error) {
            $errorMessages[] = ['key' => $key, 'msg' => $error->getMessage()];
        }

        if(count($errorMessages) > 0){
            $this->session->getFlashBag()->add('errorMessages', $errorMessages );
        }
    }

}