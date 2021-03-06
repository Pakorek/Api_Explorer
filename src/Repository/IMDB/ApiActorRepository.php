<?php

namespace App\Repository\IMDB;

use App\Entity\IMDB\ApiActor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiActor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiActor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiActor[]    findAll()
 * @method ApiActor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiActorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiActor::class);
    }

    // /**
    //  * @return ApiActor[] Returns an array of ApiActor objects
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
    public function findOneBySomeField($value): ?ApiActor
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
