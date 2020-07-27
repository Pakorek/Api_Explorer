<?php

namespace App\Repository\IMDB;

use App\Entity\IMDB\ApiProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiProgram|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiProgram|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiProgram[]    findAll()
 * @method ApiProgram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiProgram::class);
    }

    // /**
    //  * @return ApiProgram[] Returns an array of ApiProgram objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiProgram
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
