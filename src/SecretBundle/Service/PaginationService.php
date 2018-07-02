<?php
/**
 * Created by PhpStorm.
 * User: Akki
 * Date: 30.06.2018
 * Time: 23:44
 */
namespace SecretBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationService
{
    // TODO
    /*
     * Jak dodać pagiantor do __construct'ora?
     * Error msg:
     *
     * Cannot autowire service "SecretBundle\Service\PaginationService":
     * argument "$paginator" of method "__construct()" references class "Doctrine\ORM\Tools\Pagination\Paginator" but no such service exists.
     */

//    /**
//     * @var paginator
//     */
//    private $paginator;
//
//    /**
//     * PaginationService constructor.
//     * @param Paginator $paginator
//     */
//    public function __construct(Paginator $paginator)
//    {
//        $this->paginator = $paginator;
//    }

    /**
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function paginate($dql, $page = 1, $limit = 10)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $paginator;
    }
}