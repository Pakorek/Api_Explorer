<?php

namespace App\Repository;

use App\Entity\ApiSeason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiSeason|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiSeason|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiSeason[]    findAll()
 * @method ApiSeason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiSeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiSeason::class);
    }

    // /**
    //  * @return ApiSeason[] Returns an array of ApiSeason objects
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
    public function findOneBySomeField($value): ?ApiSeason
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
