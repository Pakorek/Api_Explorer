<?php

namespace App\Repository\IMDB;

use App\Entity\IMDB\ApiCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiCategory[]    findAll()
 * @method ApiCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiCategory::class);
    }

    // /**
    //  * @return ApiCategory[] Returns an array of ApiCategory objects
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
    public function findOneBySomeField($value): ?ApiCategory
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
