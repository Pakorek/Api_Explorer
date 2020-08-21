<?php

namespace App\Repository\ApiMedic;

use App\Entity\ApiMedic\BodyLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BodyLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BodyLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BodyLocation[]    findAll()
 * @method BodyLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BodyLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BodyLocation::class);
    }

    // /**
    //  * @return BodyLocation[] Returns an array of BodyLocation objects
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
    public function findOneBySomeField($value): ?BodyLocation
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
