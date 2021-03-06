<?php

namespace App\Repository\IMDB;

use App\Entity\IMDB\ApiCreator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiCreator|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiCreator|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiCreator[]    findAll()
 * @method ApiCreator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiCreatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiCreator::class);
    }

    // /**
    //  * @return ApiCreator[] Returns an array of ApiCreator objects
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
    public function findOneBySomeField($value): ?ApiCreator
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
