<?php

namespace App\Repository\ApiMedic;

use App\Entity\ApiMedic\BodySublocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BodySublocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BodySublocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BodySublocation[]    findAll()
 * @method BodySublocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BodySublocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BodySublocation::class);
    }

    // /**
    //  * @return BodySublocation[] Returns an array of BodySublocation objects
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
    public function findOneBySomeField($value): ?BodySublocation
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
