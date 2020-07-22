<?php

namespace App\Repository;

use App\Entity\ApiEpisode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiEpisode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiEpisode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiEpisode[]    findAll()
 * @method ApiEpisode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiEpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiEpisode::class);
    }

    // /**
    //  * @return ApiEpisode[] Returns an array of ApiEpisode objects
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
    public function findOneBySomeField($value): ?ApiEpisode
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
