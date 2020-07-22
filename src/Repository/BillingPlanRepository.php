<?php

namespace App\Repository;

use App\Entity\BillingPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BillingPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillingPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillingPlan[]    findAll()
 * @method BillingPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingPlan::class);
    }

    // /**
    //  * @return BillingPlan[] Returns an array of BillingPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BillingPlan
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
