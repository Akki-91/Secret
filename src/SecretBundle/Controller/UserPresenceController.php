<?php

namespace SecretBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use SecretBundle\Entity\UserInfo;
use SecretBundle\Entity\UserExperience;
use SecretBundle\Entity\UserPresence;

use SecretBundle\Form\UserInfoForm;
use SecretBundle\Form\CheckPresenceForm;
use SecretBundle\Form\AllUsersListForm;

use SecretBundle\Service\PaginationService;


use SecretBundle\Entity\Tag;
use SecretBundle\Entity\Task;
use SecretBundle\Form\TaskType;


class UserPresenceController extends Controller
{

    /**
     * @var userInfo
     */
    private $userInfo;

    /**
     * @var userInfo
     */
    private $userPresence;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PaginationService
     */
    private $paginationService;

    public function __construct(UserInfo $userInfo, EntityManagerInterface $entityManager, UserPresence $userPresence, PaginationService $paginationService )
    {
        $this->userInfo = $userInfo;
        $this->userPresence = $userPresence;
        $this->em = $entityManager;
        $this->paginationService = $paginationService;

    }

    private function checkLastPresence(int $userId)
    {
        $repo = $this->em->getRepository(UserPresence::class);
        $isChecked = $repo->findLastPresence($userId);

        if($isChecked === null){
            return false;
        }
    }

    private function userPaymentStatus($paymentDate)
    {
        $today = new \DateTime("today");
        $diffrence = $paymentDate->diff($today);
        $daysSincePayment = $diffrence->days;
        $isPaid = $paymentDate > $today;

        if($isPaid === false){
            $paymentDue = (-$daysSincePayment);
        } else {
            $paymentDue = $daysSincePayment;
        }

        if($paymentDue > 7){
            $class = 'success';
        }elseif($paymentDue <= 7 && $paymentDue >= 0){
            $class = 'warning';
        } else {
            $class = 'danger';
        }

        return $class;
    }

    private function presentUserList(int $page)
    {
        $repo = $this->em->getRepository(UserPresence::class);
        $presenceListDql = $repo->findPresenceList();

        $paginator = $this->paginationService->paginate($presenceListDql, $page);
        $maxPages = ceil($paginator->count() / 10);

        $rows = [];
        $lp = 1;
        foreach ($paginator as $row){
            $name = $row->getPresence()->getName();
            $presenceDate = $row->getPresenceDate();
            $paymentDate = $row->getPresence()->getPaymentDate();

            $class = $this->userPaymentStatus($paymentDate);

            $rows[] = ['presenceDate' => $presenceDate->format('H:i:s d-m-Y'), 'name' => $name, 'paymentDate' => $paymentDate->format('d-m-Y'), 'lp' => $lp, 'class' => $class];
            $lp++;
        }

        return [
            'rows' => $rows,
            'maxPages' => $maxPages
        ];
    }

    private function presenceCheckReturn(array $usersList, int $page, $form)
    {
        if(empty($usersList['rows'])){
            return [
                'form' => $form->createView()
            ];
        } else {
            return [
                'form' => $form->createView(),
                'rows' => $usersList['rows'],
                'page' => $page,
                'maxPages' => $usersList['maxPages']
            ];
        }
    }

    /**
     * @Route("/presenceCheck", name="presenceCheck")
     * @Template("@Secret/SecretView/presenceCheck.html.twig")
     */
    public function presenceCheckAction(Request $request)
    {
        $page = $request->query->get('page') ?? 1;
        $userInfo = $this->userInfo;
        $form = $this->createForm(CheckPresenceForm::class, $userInfo, [
            'action' => $this->generateUrl('markUserPresence')
        ]);

        $usersList = $this->presentUserList($page);
        return $this->presenceCheckReturn($usersList, $page, $form);

    }

    /**
     * @Route("/markUserPresence", name="markUserPresence")
     */
    public function markUserPresenceAction(Request $req)
    {
        $date = new \DateTime("now");
        $userInfo = $this->userInfo;
        $form = $this->createForm(CheckPresenceForm::class, $userInfo);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if ($req->request->get('checkPresence') && $form->isValid()) {
                $clubCardNumber = $userInfo->getClubCardNumber();
                $repo = $this->em->getRepository(UserInfo::class);
                $userInfo = $repo->findOneByClubCardNumber($clubCardNumber);
                $userInfo->setTotalTrainingCount($userInfo->getTotalTrainingCount() + 1);

                $numberOfEntries = $userInfo->getEntriesLeft();

                $userPresence = $this->userPresence;
                $avoidDoubleCheck = $this->checkLastPresence($userInfo->getId());

                if($avoidDoubleCheck !== false){
                    $this->addFlash('userChecked', "Użytkownik " . $userInfo->getName() . " został już dodany do listy obecnych." );
                    return $this->redirectToRoute('presenceCheck');
                }

                if($numberOfEntries === 0){
                    $this->addFlash('noEntriesLeft', "Użytkownik " . $userInfo->getName() . " wykorzystał limit treningów." );
                    return $this->redirectToRoute('presenceCheck');
                } else {
                    $userInfo->setEntriesLeft($userInfo->getEntriesLeft() - 1);
                }

                $userPresence->setPresenceDate($date);
                $userPresence->setPresence($userInfo);

                $this->em->persist($userInfo);
                $this->em->persist($userPresence);
                $this->em->flush();

                $this->addFlash('userChecked', "Użytkownik " . $userInfo->getName() . " obecny." );
                return $this->redirectToRoute('presenceCheck');
            }
        }
    }

    /**
     * @Route("/addSomeStuff", name="addSomeStuff")
     * @Template("@Secret/SecretView/addSomeStuff.html.twig")
     */
    public function addSomeStuffAction(Request $request)
    {
        $repo = $this->em->getRepository(UserInfo::class);
        $user = $repo->find(106);

        $photo = $user->getPicturePath();

        return [
            'photo' => $photo,
        ];
    }
}





